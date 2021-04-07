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

namespace GrossbergerGeorg\RunScript\Runner;

use GrossbergerGeorg\RunScript\Configuration\Script;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class ScriptStart
{
    /**
     * @var ScriptBuilder
     */
    private $scriptBuilder;

    /**
     * ScriptStart constructor.
     * @param ScriptBuilder|null $scriptBuilder
     */
    public function __construct(ScriptBuilder $scriptBuilder = null)
    {
        $this->scriptBuilder = $scriptBuilder ?? GeneralUtility::makeInstance(ScriptBuilder::class);
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
