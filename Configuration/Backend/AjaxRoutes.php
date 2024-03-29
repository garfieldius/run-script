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
    'tx_runscript_start' => [
        'path'   => '/ext/runscript/start',
        'target' => \GrossbergerGeorg\RunScript\Toolbar\StartRequestHandler::class . '::process',
    ],
    'tx_runscript_status' => [
        'path'   => '/ext/runscript/status',
        'target' => \GrossbergerGeorg\RunScript\Toolbar\StatusRequestHandler::class . '::process',
    ],
];
