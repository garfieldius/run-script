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

use GrossbergerGeorg\RunScript\Configuration\Loader;
use GrossbergerGeorg\RunScript\Configuration\Script;
use GrossbergerGeorg\RunScript\Toolbar\ToolbarItem;
use GrossbergerGeorg\RunScript\Toolbar\ViewFactory;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Fluid\View\StandaloneView;

class ToolbarItemTest extends TestCase
{
    protected function setUp(): void
    {
        if (!defined('LF')) {
            define('LF', "\n");
        }
    }

    public function testGetAdditionalAttributes()
    {
        [$subject] = $this->getSubject();

        static::assertEmpty($subject->getAdditionalAttributes());
    }

    public function testHasDropDown()
    {
        [$subject] = $this->getSubject();

        static::assertTrue($subject->hasDropdown());
    }

    public function testCheckAccess()
    {
        [$subject, $_, $loader] = $this->getSubject();
        $script = new Script('default', 'some_script', 'Hello Test', 'echo Hello', 0);

        $loader->expects(static::any())->method('getForCurrentUser')->willReturn([$script]);

        static::assertTrue($subject->checkAccess());
    }

    public function testGetDropDown()
    {
        [$subject, $viewFactory, $loader] = $this->getSubject();
        $expected = 'HTML';
        $scripts = [new Script('default', 'some_script', 'Hello Test', 'echo Hello', 0)];

        $loader->expects(static::any())->method('getForCurrentUser')->willReturn($scripts);

        $view = $this->createMock(StandaloneView::class);
        $view->expects(static::any())->method('assign')->willReturn($view);
        $view->expects(static::any())->method('render')->willReturn($expected);

        $viewFactory->expects(static::any())->method('createView')->with(static::equalTo('Dropdown'))->willReturn($view);

        $actual = $subject->getDropdown();

        static::assertSame($expected, $actual);
    }

    public function testGetIndex()
    {
        [$subject] = $this->getSubject();

        static::assertTrue($subject->getIndex() >= 0 && $subject->getIndex() <= 99);
    }

    public function testGetItem()
    {
        [$subject, $viewFactory, $loader, $pageRenderer] = $this->getSubject();
        $expected = 'HTML';
        $scripts = [new Script('default', 'some_script', 'Hello Test', 'echo Hello', 0)];

        $pageRenderer->expects(self::once())->method('addInlineLanguageLabelFile');

        $loader->expects(static::any())->method('getForCurrentUser')->willReturn($scripts);

        $view = $this->createMock(StandaloneView::class);
        $view->expects(static::any())->method('assign')->willReturn($view);
        $view->expects(static::any())->method('render')->willReturn($expected);

        $viewFactory->expects(static::any())->method('createView')->with(static::equalTo('Item'))->willReturn($view);

        $actual = $subject->getItem();

        static::assertSame($expected, $actual);
    }
    private function getSubject(): array
    {
        $viewFactory = $this->createMock(ViewFactory::class);
        $loader = $this->createMock(Loader::class);
        $pageRenderer = $this->createMock(PageRenderer::class);
        $subject = new ToolbarItem($viewFactory, $loader, $pageRenderer);

        return [$subject, $viewFactory, $loader, $pageRenderer];
    }
}
