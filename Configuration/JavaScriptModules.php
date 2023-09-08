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

return [
    'dependencies' => [
        'core',
        'backend',
    ],
    'imports' => [
        '@grossberger-georg/run-script/' => 'EXT:run_script/Resources/Public/JavaScript/',
    ],
];
