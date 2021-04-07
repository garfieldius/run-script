<?php

/*
 * (c) 2021 Georg Großberger <contact@grossberger-ge.org>
 *
 * This file is free software; you can redistribute it and/or
 * modify it under the terms of the MIT license
 *
 * For the full copyright and license information see the file
 * LICENSE or <https://opensource.org/licenses/MIT>
 */

$GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1614164589] = \GrossbergerGeorg\RunScript\Toolbar\ToolbarItem::class;

if ($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['run_script']['enableDemo'] ?? false) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['run_script']['tx_runscript_sleep1'] = [
        'label'  => 'LLL:EXT:run_script/Resources/Private/Language/locallang.xlf:demo.sleep.script',
        'script' => 'EXT:run_script/Resources/Private/Scripts/demo.sh',
    ];
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['run_script']['tx_runscript_sleep2'] = [
        'label'  => 'LLL:EXT:run_script/Resources/Private/Language/locallang.xlf:demo.sleep.command',
        'script' => 'sleep 30',
    ];
}

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)->registerIcon(
    'runscript-terminal',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:run_script/Resources/Public/Icons/Extension.svg']
);
