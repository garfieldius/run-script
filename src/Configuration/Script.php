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

namespace GrossbergerGeorg\RunScript\Configuration;

/**
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class Script
{
    /**
     * Script constructor.
     * @param string $icon
     * @param string $key
     * @param string $label
     * @param string $script
     * @param int $reloadBackend
     */
    public function __construct(
        public readonly string $icon,
        public readonly string $key,
        public readonly string $label,
        public readonly string $script,
        public readonly int $reloadBackend,
    ) {
    }
}
