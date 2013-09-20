<?php
namespace Aura\Sql_Schema_Bundle;

trait Assertions
{
    protected function assertSameSql($expect, $actual)
    {
        $expect = trim($expect);
        $expect = preg_replace('/^\s*/m', '', $expect);
        $expect = preg_replace('/\s*$/m', '', $expect);
        
        $actual = trim($actual);
        $actual = preg_replace('/^\s*/m', '', $actual);
        $actual = preg_replace('/\s*$/m', '', $actual);
        
        $this->assertSame($expect, $actual);
    }
}
