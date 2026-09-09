<?php
namespace Aura\SqlSchema;

class ColumnTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
    public function testConstruct()
    {
        $info = array(
            'name' => 'cost',
            'type' => 'numeric',
            'size' => 10,
            'scale' => 2,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
        );

        $col = new Column(
            $info['name'],
            $info['type'],
            $info['size'],
            $info['scale'],
            $info['notnull'],
            $info['default'],
            $info['autoinc'],
            $info['primary']
        );

        foreach ($info as $key => $expect) {
            $this->assertTrue(isset($col->$key));
            $this->assertSame($expect, $col->$key);
        }
    }

    public function test__set_state()
    {
        $info = array(
            'name' => 'cost',
            'type' => 'numeric',
            'size' => 10,
            'scale' => 2,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
        );

        $col = new Column(
            $info['name'],
            $info['type'],
            $info['size'],
            $info['scale'],
            $info['notnull'],
            $info['default'],
            $info['autoinc'],
            $info['primary']
        );

        // PHP 8.2 changed var_export() to emit fully-qualified class
        // names, so from 8.2 onward the export carries a leading
        // backslash. Normalize it away so one expectation covers both.
        $actual = ltrim(var_export($col, true), '\\');
        $expect = <<<EXPECT
Aura\SqlSchema\Column::__set_state(array(
   'name' => 'cost',
   'type' => 'numeric',
   'size' => 10,
   'scale' => 2,
   'notnull' => true,
   'default' => NULL,
   'autoinc' => false,
   'primary' => false,
))
EXPECT;
        if (defined('HHVM_VERSION')) {
            $expect = str_replace('   ', '  ', $expect);
        }

        // check the export
        $this->assertSame($expect, $actual);

        // check __set_state() directly
        $col = Column::__set_state($info);
        $actual = ltrim(var_export($col, true), '\\');
        $this->assertSame($expect, $actual);
    }
}
