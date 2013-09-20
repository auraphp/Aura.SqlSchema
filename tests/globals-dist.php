<?php
/**
 * Mysql
 */
$GLOBALS['Aura\Sql_Schema_Bundle\Connection\MysqlTest']['connection_params'] = [
    'dsn' => [
        'host' => '127.0.0.1',
    ],
    'username' => 'root',
    'password' => '',
    'options' => [],
];

$GLOBALS['Aura\Sql_Schema_Bundle\Connection\MysqlTest']['expect_dsn_string'] = 'mysql:host=127.0.0.1';

$GLOBALS['Aura\Sql_Schema_Bundle\Connection\MysqlTest']['db_setup_class'] = 'Aura\Sql_Schema_Bundle\DbSetup\Mysql';

/**
 * Pgsql
 */
$GLOBALS['Aura\Sql_Schema_Bundle\Connection\PgsqlTest']['connection_params'] = [
    'dsn' => [
        'host' => '127.0.0.1',
        'dbname' => 'test',
    ],
    'username' => 'postgres',
    'password' => '',
    'options' => [],
];

$GLOBALS['Aura\Sql_Schema_Bundle\Connection\PgsqlTest']['expect_dsn_string'] = 'pgsql:host=127.0.0.1;dbname=test';

$GLOBALS['Aura\Sql_Schema_Bundle\Connection\PgsqlTest']['db_setup_class'] = 'Aura\Sql_Schema_Bundle\DbSetup\Pgsql';

/**
 * Sqlite
 */
$GLOBALS['Aura\Sql_Schema_Bundle\Connection\SqliteTest']['connection_params'] = [
    'dsn' => ':memory:',
];
    
$GLOBALS['Aura\Sql_Schema_Bundle\Connection\SqliteTest']['expect_dsn_string'] = 'sqlite::memory:';

$GLOBALS['Aura\Sql_Schema_Bundle\Connection\SqliteTest']['db_setup_class'] = 'Aura\Sql_Schema_Bundle\DbSetup\Sqlite';
