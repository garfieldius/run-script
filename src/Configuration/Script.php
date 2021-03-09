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

namespace GrossbergerGeorg\RunScript\Configuration;

/**
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class Script
{
    /**
     * @var string
     */
    private $icon;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $script;

    /**
     * @var int
     */
    private $reloadBackend;

    /**
     * Script constructor.
     * @param string $icon
     * @param string $key
     * @param string $label
     * @param string $script
     * @param int $reloadBackend
     */
    public function __construct(string $icon, string $key, string $label, string $script, int $reloadBackend)
    {
        $this->icon = $icon;
        $this->key = $key;
        $this->label = $label;
        $this->script = $script;
        $this->reloadBackend = $reloadBackend;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getScript(): string
    {
        return $this->script;
    }

    /**
     * @return int
     */
    public function getReloadBackend(): int
    {
        return $this->reloadBackend;
    }
}
