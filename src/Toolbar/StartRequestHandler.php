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

use GrossbergerGeorg\RunScript\Configuration\Loader;
use GrossbergerGeorg\RunScript\Configuration\Script;
use GrossbergerGeorg\RunScript\Runner\ScriptStart;
use GrossbergerGeorg\RunScript\Runner\ScriptStatus;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Georg Großberger <g.grossberger@supseven.at>
 */
class StartRequestHandler extends AjaxRequestHandler
{
    /**
     * @var ScriptStart
     */
    private $starter;

    /**
     * @var ScriptStatus
     */
    private $status;

    /**
     * AjaxRunner constructor.
     * @param ScriptStart|null $starter
     * @param ScriptStatus|null $status
     * @param Loader|null $configurationLoader
     * @param LanguageService|null $languageService
     */
    public function __construct(
        ScriptStart $starter = null,
        ScriptStatus $status = null,
        Loader $configurationLoader = null,
        LanguageService $languageService = null
    ) {
        $this->starter = $starter ?? GeneralUtility::makeInstance(ScriptStart::class);
        $this->status = $status ?? GeneralUtility::makeInstance(ScriptStatus::class);
        $this->languageService = $languageService ?? LanguageService::createFromUserPreferences($GLOBALS['BE_USER']);
        $this->configurationLoader = $configurationLoader ?? GeneralUtility::makeInstance(Loader::class);
    }

    protected function handleScript(Script $script): ResultMessage
    {
        $code = $this->starter->startScript($script);

        if ($code) {
            $pid = $this->status->getScriptProcessID($script);

            if ($pid) {
                $result = ResultMessage::message($this->ll('message.alreadyrunning', 'Another instance of the script is already running'), true);
            } else {
                $result = ResultMessage::error($this->ll('error.nostart', 'Error during start of script'));
            }
        } else {
            $result = ResultMessage::message($this->ll('message.started', 'Script started'), true, true);
        }

        return $result;
    }
}
