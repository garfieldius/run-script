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

namespace GrossbergerGeorg\RunScript\Runner;

use GrossbergerGeorg\RunScript\Configuration\Script;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class ScriptStatus
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
