<?php
namespace Aura\Sql_Schema;

class PgsqlSchemaTest extends AbstractSchemaTest
{
    protected $extension = 'pdo_pgsql';
    
    protected $pdo_type = 'pgsql';
    
    protected $expect_fetch_table_list = [
        'aura_test_schema1.aura_test_table',
        'aura_test_schema2.aura_test_table'
    ];
    
    protected $expect_fetch_table_list_schema = ['aura_test_table'];
    
    protected $expect_fetch_table_cols = [
        'id' => [
            'name' => 'id',
            'type' => 'integer',
            'size' => null,
            'scale' => null,
            'default' => null,
            'notnull' => true,
            'primary' => true,
            'autoinc' => true,
        ],
        'name' => [
            'name' => 'name',
            'type' => 'character varying',
            'size' => 50,
            'scale' => null,
            'default' => null,
            'notnull' => true,
            'primary' => false,
            'autoinc' => false,
        ],
        'test_size_scale' => [
            'name' => 'test_size_scale',
            'type' => 'numeric',
            'size' => 7,
            'scale' => 3,
            'default' => null,
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ],
        'test_default_null' => [
            'name' => 'test_default_null',
            'type' => 'character',
            'size' => 3,
            'scale' => null,
            'default' => null,
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ],
        'test_default_string' => [
            'name' => 'test_default_string',
            'type' => 'character varying',
            'size' => 7,
            'scale' => null,
            'default' => 'string',
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ],
        'test_default_number' => [
            'name' => 'test_default_number',
            'type' => 'numeric',
            'size' => 5,
            'scale' => null,
            'default' => '12345',
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ],
        'test_default_ignore' => [
            'name' => 'test_default_ignore',
            'type' => 'timestamp without time zone',
            'size' => null,
            'scale' => null,
            'default' => null,
            'notnull' => false,
            'primary' => false,
            'autoinc' => false,
        ],
    ];
}
