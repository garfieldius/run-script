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
class ScriptStart
{
    public function __construct(
        private readonly ScriptBuilder $scriptBuilder
    ) {
    }

    public function startScript(Script $script): int
    {
        $bashScript = $this->scriptBuilder->createScript($script);
        $statFile = substr($bashScript, 0, -3) . '.stat';
        $cmd = 'nohup bash ' . escapeshellarg($bashScript) . ' > /dev/null 2>&1 &';

        exec($cmd);

        while (!is_file($statFile)) {
            usleep(10);
            clearstatcache();
        }

        usleep(100);

        return (int) trim(file_get_contents($statFile));
    }
}
