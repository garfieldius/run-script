<?php
declare(strict_types=1);

/*
 * (c) 2021 Georg Großberger <contact@grossberger-ge.org>
 *
 * This file is free software; you can redistribute it and/or
 * modify it under the terms of the Mozilla Public License 2.0
 *
 * For the full copyright and license information see the file
 * LICENSE or <https://www.mozilla.org/en-US/MPL/2.0/>
 */

namespace GrossbergerGeorg\RunScript\Toolbar;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class ViewFactory
{
    /**
     * @var string
     */
    private $baseDir;

    /**
     * ViewFactory constructor.
     * @param string $baseDir
     */
    public function __construct(string $baseDir = null)
    {
        $this->baseDir = $baseDir ?: ExtensionManagementUtility::extPath('run_script') . 'Resources/Private/';
    }

    public function createView(string $template): ViewInterface
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateRootPaths([$this->baseDir . 'Templates/']);
        $view->setPartialRootPaths([$this->baseDir . 'Partials/']);
        $view->setLayoutRootPaths([$this->baseDir . 'Layouts/']);
        $view->setTemplate($template);
        $view->getRequest()->setControllerExtensionName('RunScript');

        return $view;
    }
}
