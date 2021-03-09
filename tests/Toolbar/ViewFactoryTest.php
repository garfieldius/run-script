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

namespace GrossbergerGeorg\RunScript\Tests\Toolbar;

use GrossbergerGeorg\RunScript\Toolbar\ViewFactory;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\View\StandaloneView;

class ViewFactoryTest extends TestCase
{
    public function testCreateView()
    {
        $template = 'Tmpl';
        $basedir = '/my/root/path/';

        $request = $this->createMock(Request::class);
        $request->expects(static::once())->method('setControllerExtensionName')->with(self::equalTo('RunScript'));

        $view = $this->createMock(StandaloneView::class);
        $view->expects(static::once())->method('setTemplateRootPaths')->with(static::equalTo([$basedir . 'Templates/']));
        $view->expects(static::once())->method('setTemplate')->with(static::equalTo($template));
        $view->expects(static::once())->method('getRequest')->willReturn($request);

        GeneralUtility::addInstance(StandaloneView::class, $view);

        $subject = new ViewFactory($basedir);
        $subject->createView($template);
    }
}
