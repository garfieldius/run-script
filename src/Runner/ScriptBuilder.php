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
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class ScriptBuilder
{
    public function getScriptPath(Script $script): string
    {
        return Environment::getVarPath() . '/run/tx_runscript/' . $script->getKey() . '.sh';
    }

    public function createScript(Script $script): string
    {
        $exports = [];
        $file = $this->getScriptPath($script);

        foreach ([getenv(), $_ENV] as $variables) {
            foreach ($variables as $name => $value) {
                $exports[$name] = "export {$name}=" . escapeshellarg((string) $value);
            }
        }

        $template = file_get_contents(GeneralUtility::getFileAbsFileName('EXT:run_script/Resources/Private/Scripts/template.sh'));
        $template = str_replace([
            '__BASE_DIR__',
            '__SCRIPT_KEY__',
            '__VARS__',
            '__SCRIPT__',
        ], [
            escapeshellarg(Environment::getProjectPath()),
            escapeshellarg($script->getKey()),
            implode("\n    ", $exports),
            $script->getScript(),
        ], $template);

        $dir = dirname($file);

        if (!is_dir($dir)) {
            mkdir($dir, octdec($GLOBALS['TYPO3_CONF_VARS']['SYS']['folderCreateMask']), true);
        }

        file_put_contents($file, $template);
        chmod($file, 0750);

        return $file;
    }
}
