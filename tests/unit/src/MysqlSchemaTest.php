<?php
namespace Aura\SqlSchema;

class MysqlSchemaTest extends AbstractSchemaTest
{
    protected $extension = 'pdo_mysql';

    protected $pdo_type = 'mysql';

    protected $expect_fetch_table_list = array('aura_test_table');

    protected $expect_fetch_table_list_schema = array('aura_test_table');

    protected $expect_fetch_table_cols = array(
        'id' => array(
            'name' => 'id',
            'type' => 'int',
            'size' => 11,
            'scale' => null,
            'default' => null,
            'notnull' => true,
            'primary' => true,
            'autoinc' => true,
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'size' => 50,
            'scale' => null,
            'default' => null,
            'notnull' => true,
            'primary' => false,
            'autoinc' => false,
        ),
        'test_size_scale' => array(
            'name' => 'test_size_scale',
            'type' => 'decimal',
            'size' => 7,
            'scale' => 3,
            'default' => null,
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ),
        'test_default_null' => array(
            'name' => 'test_default_null',
            'type' => 'char',
            'size' => 3,
            'scale' => null,
            'default' => null,
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ),
        'test_default_string' => array(
            'name' => 'test_default_string',
            'type' => 'varchar',
            'size' => 7,
            'scale' => null,
            'default' => 'string',
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ),
        'test_default_number' => array(
            'name' => 'test_default_number',
            'type' => 'decimal',
            'size' => 5,
            'scale' => null,
            'default' => '12345',
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ),
        'test_default_ignore' => array(
            'name' => 'test_default_ignore',
            'type' => 'timestamp',
            'size' => null,
            'scale' => null,
            'default' => null,
            'notnull' => true,
            'primary' => false,
            'autoinc' => false,
        ),
    );

    protected $expect_quote_name = "`one`.`two`";
}
