<?php
declare(strict_types=1);

/*
 * (c) 2023 Georg Großberger <contact@grossberger-ge.org>
 *
 * This file is free software; you can redistribute it and/or
 * modify it under the terms of the MIT license
 *
 * For the full copyright and license information see the file
 * LICENSE or <https://opensource.org/licenses/MIT>
 */

namespace GrossbergerGeorg\RunScript\Toolbar;

use GrossbergerGeorg\RunScript\Configuration\Loader;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class ToolbarItem implements ToolbarItemInterface
{
    public function __construct(
        private readonly ViewFactory $viewFactory,
        private readonly Loader $configurationsLoader,
        private readonly PageRenderer $pageRenderer,
    ) {
    }

    public function checkAccess()
    {
        return count($this->configurationsLoader->getForCurrentUser()) > 0;
    }

    public function getItem()
    {
        $this->pageRenderer->addInlineLanguageLabelFile('EXT:run_script/Resources/Private/Language/locallang.xlf');

        return $this->renderView('Item');
    }

    public function hasDropDown()
    {
        return true;
    }

    public function getDropDown()
    {
        return $this->renderView('Dropdown');
    }

    public function getAdditionalAttributes()
    {
        return [];
    }

    public function getIndex()
    {
        return 10;
    }

    /**
     * @param string $string
     * @return string
     */
    private function renderView(string $string): string
    {
        return $this->viewFactory
            ->createView($string)
            ->assign('items', $this->configurationsLoader->getForCurrentUser())
            ->render();
    }
}
