<?php
namespace Aura\SqlSchema;

class SqliteSchemaTest extends AbstractSchemaTest
{
    protected $extension = 'pdo_sqlite';

    protected $pdo_type = 'sqlite';

    protected $expect_fetch_table_list = array('aura_test_table', 'sqlite_sequence');

    protected $expect_fetch_table_list_schema = array('aura_test_table', 'sqlite_sequence');

    protected $expect_fetch_table_cols = array(
        'id' => array(
            'name' => 'id',
            'type' => 'integer',
            'size' => null,
            'scale' => null,
            'default' => null,
            'notnull' => false,
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
            'type' => 'numeric',
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
            'type' => 'numeric',
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
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ),
    );
}
