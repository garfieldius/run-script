<?php
declare(strict_types=1);

/*
 * (c) 2021 Georg Großberger <contact@grossberger-ge.org>
 *
 * This file is free software; you can redistribute it and/or
 * modify it under the terms of the MIT license
 *
 * For the full copyright and license information see the file
 * LICENSE or <https://opensource.org/licenses/MIT>
 */

namespace GrossbergerGeorg\RunScript\Tests\Configuration;

use GrossbergerGeorg\RunScript\Configuration\Loader;
use GrossbergerGeorg\RunScript\Configuration\Script;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Package\Package;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class LoaderTest extends TestCase
{
    public function testLoadAll(): void
    {
        $extensionKey = 'my_ext';
        $scriptPath = '/Resources/Private/Scripts/my_script.sh';
        $basePath = '/root/path/of/my_ext/';
        $expected = new Script('runscript-terminal', 'tx_runscript_test', 'test', $basePath . ltrim($scriptPath, '/'), 5);
        $ll = 'LLL:TEST';
        $scriptRefPath = 'EXT:' . $extensionKey . $scriptPath;

        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['run_script']['tx_runscript_test'] = [
            'script'        => $scriptRefPath,
            'label'         => $ll,
            'reloadBackend' => (string) $expected->getReloadBackend(),
        ];

        $package = $this->createMock(Package::class);
        $package->expects(static::any())->method('getPackagePath')->willReturn($basePath);

        $packageManager = $this->createMock(PackageManager::class);
        $packageManager->expects(static::any())->method('resolvePackagePath')->with(static::equalTo($scriptRefPath))->willReturn($expected->getScript());
        $packageManager->expects(static::any())->method('getPackage')->with(static::equalTo($extensionKey))->willReturn($package);
        $packageManager->expects(static::any())->method('isPackageActive')->with(static::equalTo($extensionKey))->willReturn(true);

        ExtensionManagementUtility::setPackageManager($packageManager);

        $languageService = $this->createMock(LanguageService::class);
        $languageService->expects(static::any())->method('sL')->with(static::equalTo($ll))->willReturn($expected->getLabel());

        $backendUser = $this->createMock(BackendUserAuthentication::class);

        $subject = new Loader($languageService, $backendUser);
        $actual = $subject->loadAll();

        static::assertEquals([$expected], $actual);
    }

    public function testLoadForCurrentUser(): void
    {
        $script = '/bin/sh -c "echo Hello"';

        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['run_script']['tx_runscript_test1'] = [
            'script'        => $script,
            'label'         => 'Script 1',
            'reloadBackend' => 4,
        ];
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['run_script']['tx_runscript_test2'] = [
            'script'        => $script,
            'label'         => 'Script 2',
        ];
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['run_script']['tx_runscript_test3'] = [
            'script'        => $script,
            'label'         => 'Script 3',
        ];

        $TSConfig = [
            'tx_runscript.' => [
                'allowed' => 'tx_runscript_test1, tx_runscript_test3',
            ],
        ];

        $languageService = $this->createMock(LanguageService::class);
        $backendUser = $this->createMock(BackendUserAuthentication::class);

        $backendUser->expects(static::any())->method('isAdmin')->willReturn(false);
        $backendUser->expects(static::any())->method('getTSConfig')->willReturn($TSConfig);

        $expected = [
            new Script('runscript-terminal', 'tx_runscript_test1', 'Script 1', $script, 4),
            new Script('runscript-terminal', 'tx_runscript_test3', 'Script 3', $script, 0),
        ];

        $subject = new Loader($languageService, $backendUser);

        $actual = $subject->getForCurrentUser();

        static::assertEquals($expected, $actual);
    }
}
