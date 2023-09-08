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

namespace GrossbergerGeorg\RunScript\Tests\Toolbar;

use GrossbergerGeorg\RunScript\Toolbar\ViewFactory;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class ViewFactoryTest extends TestCase
{
    public function testCreateView()
    {
        $template = 'Tmpl';
        $basedir = '/my/root/path/';

        $view = $this->createMock(StandaloneView::class);
        $view->expects(static::once())->method('setTemplateRootPaths')->with(static::equalTo([$basedir . 'Templates/']));
        $view->expects(static::once())->method('setTemplate')->with(static::equalTo($template));

        GeneralUtility::addInstance(StandaloneView::class, $view);

        $subject = new ViewFactory();
        $subject->createView($template, $basedir);
    }
}
