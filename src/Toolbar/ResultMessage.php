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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Georg Großberger <g.grossberger@supseven.at>
 */
class ResultMessage implements \JsonSerializable
{
    /**
     * @param bool $started
     * @param bool $running
     * @param string $message
     * @param string $error
     */
    public function __construct(
        public readonly bool $started,
        public readonly bool $running,
        public readonly string $message,
        public readonly string $error
    ) {
    }

    public static function error(string $error, $running = false, $started = false): self
    {
        return GeneralUtility::makeInstance(self::class, $started, $running, '', $error);
    }

    public static function message(string $message, $running = false, $started = false): self
    {
        return GeneralUtility::makeInstance(self::class, $started, $running, $message, '');
    }

    public function jsonSerialize()
    {
        return [
            'started' => $this->started,
            'running' => $this->running,
            'message' => $this->message ?: null,
            'error'   => $this->error ?: null,
        ];
    }
}
