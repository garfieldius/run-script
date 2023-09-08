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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class ViewFactory
{
    public function createView(string $template, string $baseDir = ''): ViewInterface
    {
        if (!$baseDir) {
            $baseDir = ExtensionManagementUtility::extPath('run_script') . 'Resources/Private/';
        }

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplateRootPaths([$baseDir . 'Templates/']);
        $view->setPartialRootPaths([$baseDir . 'Partials/']);
        $view->setLayoutRootPaths([$baseDir . 'Layouts/']);
        $view->setTemplate($template);

        return $view;
    }
}
