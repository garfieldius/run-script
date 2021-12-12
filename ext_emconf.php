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

$EM_CONF[$_EXTKEY] = [
    'title'            => 'Run Script',
    'description'      => 'Run a server-side script via the backend',
    'category'         => 'be',
    'author'           => 'Georg Großberger',
    'author_email'     => 'contact@grossberger-ge.org',
    'state'            => 'stable',
    'internal'         => '',
    'uploadfolder'     => '0',
    'createDirs'       => '',
    'clearCacheOnLoad' => 0,
    'version'          => '0.3.2',
    'constraints'      => [
        'depends' => [
            'typo3' => '9.5.0-11.5.999',
        ],
    ],
];
