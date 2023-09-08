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

namespace GrossbergerGeorg\RunScript\Tests\Runner;

use GrossbergerGeorg\RunScript\Configuration\Script;
use GrossbergerGeorg\RunScript\Runner\ScriptBuilder;
use GrossbergerGeorg\RunScript\Runner\ScriptStart;
use PHPUnit\Framework\TestCase;

class ScriptStartTest extends TestCase
{
    private $scriptFile = '';

    private $statFile = '';

    protected function setUp(): void
    {
        $this->scriptFile = sys_get_temp_dir() . '/tx_runscript.sh';
        $this->statFile = sys_get_temp_dir() . '/tx_runscript.stat';

        $this->tearDown();
    }

    protected function tearDown(): void
    {
        @unlink($this->scriptFile);
        @unlink($this->statFile);
    }

    public function testStartScript(): void
    {
        $cmd = 'echo 0 > ' . $this->statFile;
        file_put_contents($this->scriptFile, $cmd);

        $script = new Script('icon', 'key', 'label', $cmd, 0);
        $builder = $this->createMock(ScriptBuilder::class);
        $builder->expects(static::any())->method('createScript')->with(static::equalTo($script))->willReturn($this->scriptFile);

        $subject = new ScriptStart($builder);
        $code = $subject->startScript($script);

        static::assertEquals(0, $code);
    }
}
