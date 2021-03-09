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

namespace GrossbergerGeorg\RunScript\Tests\Runner;

use GrossbergerGeorg\RunScript\Configuration\Script;
use GrossbergerGeorg\RunScript\Runner\ScriptBuilder;
use GrossbergerGeorg\RunScript\Runner\ScriptStatus;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ScriptStatusTest extends TestCase
{
    public function testGetStatusOfScript(): void
    {
        $expected = 123;
        $fs = vfsStream::setup('typo3', 0755, [
            'script.pid' => "{$expected}\n",
            'script.sh'  => 'echo Hello World',
        ]);
        $script = new Script('icon', 'key', 'label', 'script', 0);
        $builder = $this->createMock(ScriptBuilder::class);
        $builder->expects(static::any())->method('getScriptPath')->with(static::equalTo($script))->willReturn($fs->getChild('script.sh')->url());

        $subject = new ScriptStatus($builder);
        $actual = $subject->getScriptProcessID($script);

        static::assertEquals($expected, $actual);
    }
}
