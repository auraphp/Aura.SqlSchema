<?php
namespace Aura\SqlSchema;

class PgsqlSchemaTest extends AbstractSchemaTest
{
    protected $extension = 'pdo_pgsql';

    protected $pdo_type = 'pgsql';

    protected $expect_fetch_table_list = array(
        'aura_test_schema1.aura_test_table',
        'aura_test_schema2.aura_test_table'
    );

    protected $expect_fetch_table_list_schema = array('aura_test_table');

    protected $expect_fetch_table_cols = array(
        'id' => array(
            'name' => 'id',
            'type' => 'integer',
            'size' => null,
            'scale' => null,
            'default' => null,
            'notnull' => true,
            'primary' => true,
            'autoinc' => true,
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'character varying',
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
            'type' => 'character',
            'size' => 3,
            'scale' => null,
            'default' => null,
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ),
        'test_default_string' => array(
            'name' => 'test_default_string',
            'type' => 'character varying',
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
            'type' => 'timestamp without time zone',
            'size' => null,
            'scale' => null,
            'default' => null,
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ),
    );
}
