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
use GrossbergerGeorg\RunScript\Runner\ScriptStatus;

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
     * @param ScriptStatus $status
     * @param Loader $configurationLoader
     */
    public function __construct(ScriptStatus $status, Loader $configurationLoader)
    {
        $this->status = $status;
        $this->configurationLoader = $configurationLoader;
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
