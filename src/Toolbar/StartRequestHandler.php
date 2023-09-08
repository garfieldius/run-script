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

use GrossbergerGeorg\RunScript\Configuration\Loader;
use GrossbergerGeorg\RunScript\Configuration\Script;
use GrossbergerGeorg\RunScript\Runner\ScriptStart;
use GrossbergerGeorg\RunScript\Runner\ScriptStatus;

/**
 * @author Georg Großberger <g.grossberger@supseven.at>
 */
class StartRequestHandler extends AjaxRequestHandler
{
    /**
     * @var ScriptStart
     */
    private ScriptStart $starter;

    /**
     * @var ScriptStatus
     */
    private ScriptStatus $status;

    /**
     * @param Loader $configurationLoader
     * @param ScriptStart $starter
     * @param ScriptStatus $status
     */
    public function __construct(Loader $configurationLoader, ScriptStart $starter, ScriptStatus $status)
    {
        $this->configurationLoader = $configurationLoader;
        $this->starter = $starter;
        $this->status = $status;
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
