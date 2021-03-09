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
use GrossbergerGeorg\RunScript\Runner\ScriptStatus;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Georg Großberger <g.grossberger@supseven.at>
 */
class StatusRequestHandler extends AjaxRequestHandler
{
    /**
     * @var ScriptStatus
     */
    private $status;

    /**
     * @param ScriptStatus|null $status
     * @param Loader|null $configurationLoader
     * @param LanguageService|null $languageService
     */
    public function __construct(ScriptStatus $status = null, Loader $configurationLoader = null, LanguageService $languageService = null)
    {
        $this->status = $status ?? GeneralUtility::makeInstance(ScriptStatus::class);
        $this->languageService = $languageService ?? LanguageService::createFromUserPreferences($GLOBALS['BE_USER']);
        $this->configurationLoader = $configurationLoader ?? GeneralUtility::makeInstance(Loader::class);
    }

    protected function handleScript(Script $script): ResultMessage
    {
        $pid = $this->status->getScriptProcessID($script);

        if ($pid > 0) {
            $result = ResultMessage::message($this->ll('message.running', 'Script is still running'), true);
        } else {
            $result = ResultMessage::message($this->ll('message.idle', 'Script is not running'), false);
        }

        return $result;
    }
}
