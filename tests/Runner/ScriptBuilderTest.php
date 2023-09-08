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

namespace GrossbergerGeorg\RunScript\Tests\Runner;

use GrossbergerGeorg\RunScript\Configuration\Script;
use GrossbergerGeorg\RunScript\Runner\ScriptBuilder;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Core\ApplicationContext;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class ScriptBuilderTest extends TestCase
{
    public function testGetScriptPath(): void
    {
        $script = new Script('icon', 'key', 'label', 'echo Hello', 0);
        $subject = new ScriptBuilder();
        $path = $this->initEnvironment();

        $expected = $path . '/var/run/tx_runscript/key.sh';
        $actual = $subject->getScriptPath($script);

        static::assertSame($expected, $actual);
    }

    public function testCreateScript(): void
    {
        $script = new Script('icon', 'key', 'label', 'echo Hello', 0);
        $subject = new ScriptBuilder();
        $this->initEnvironment();

        $_ENV['foo'] = 'bar';

        $extPath = dirname(realpath(__DIR__), 2);
        $package = $this->createMock(Package::class);
        $packageManager = $this->createMock(PackageManager::class);
        $fullPathTemplate = $extPath . '/Resources/Private/Scripts/template.sh';

        if (method_exists(PackageManager::class, 'resolvePackagePath')) {
            $packageManager->expects(static::any())
                ->method('resolvePackagePath')
                ->with(static::equalTo('EXT:run_script/Resources/Private/Scripts/template.sh'))
                ->willReturn($fullPathTemplate);
        }

        $packageManager->expects(static::any())->method('isPackageActive')->with(static::equalTo('run_script'))->willReturn(true);
        $packageManager->expects(static::any())->method('getPackage')->with(static::equalTo('run_script'))->willReturn($package);
        $package->expects(static::any())->method('getPackagePath')->willReturn($extPath . '/');

        ExtensionManagementUtility::setPackageManager($packageManager);

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['folderCreateMask'] = 0755;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fileCreateMask'] = 0644;

        $file = $subject->createScript($script);

        static::assertFileExists($file);

        $content = file_get_contents($file);

        static::assertStringContainsString($script->script, $content);
        static::assertStringContainsString('export foo=' . escapeshellarg('bar'), $content);
    }

    /**
     * @return string
     */
    private function initEnvironment(): string
    {
        $path = vfsStream::setup('typo3', 0775, [
            'public' => [
                'index.php' => '',
            ],
            'var' => [
                'run' => [
                    'tx_runscript' => [],
                ],
            ],
            'config' => [],
        ])->url();

        Environment::initialize(
            new ApplicationContext('Testing'),
            true,
            true,
            $path,
            $path . '/public',
            $path . '/var',
            $path . '/config',
            $path . '/public/index.php',
            'UNIX'
        );

        return $path;
    }
}
