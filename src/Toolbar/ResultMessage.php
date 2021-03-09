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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Georg Großberger <g.grossberger@supseven.at>
 */
class ResultMessage implements \JsonSerializable
{
    /**
     * @var bool
     */
    private $started = false;

    /**
     * @var bool
     */
    private $running = false;

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var string
     */
    private $error = '';

    public static function error(string $error, $running = false, $started = false): self
    {
        $result = GeneralUtility::makeInstance(self::class);
        $result->error = $error;
        $result->started = $started;
        $result->running = $running;

        return $result;
    }

    public static function message(string $message, $running = false, $started = false): self
    {
        $result = GeneralUtility::makeInstance(self::class);
        $result->message = $message;
        $result->started = $started;
        $result->running = $running;

        return $result;
    }

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->started;
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->running;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    public function jsonSerialize()
    {
        return [
            'started' => $this->isStarted(),
            'running' => $this->isRunning(),
            'message' => $this->getMessage() ?: null,
            'error'   => $this->getError() ?: null,
        ];
    }
}
