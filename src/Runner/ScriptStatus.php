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

namespace GrossbergerGeorg\RunScript\Runner;

use GrossbergerGeorg\RunScript\Configuration\Script;

/**
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class ScriptStatus
{
    public function __construct(
        private readonly ScriptBuilder $scriptBuilder
    ) {
    }

    public function getScriptProcessID(Script $script): int
    {
        $bashScript = $this->scriptBuilder->getScriptPath($script);
        $pidFile = substr($bashScript, 0, -3) . '.pid';

        // Return PID of executing process
        if (is_file($pidFile)) {
            return (int) trim(file_get_contents($pidFile));
        }

        return 0;
    }
}
