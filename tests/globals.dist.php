<?php
/**
 * Mysql
 */
$GLOBALS['Aura\Sql_Schema\Setup\MysqlSetup']['pdo_params'] = array(
    'dsn'           => 'mysql:host=127.0.0.1',
    'username'      => 'root',
    'password'      => '',
    'options'       => array()
);

/**
 * Pgsql
 */
$GLOBALS['Aura\Sql_Schema\Setup\PgsqlSetup']['pdo_params'] = array(
    'dsn'           => 'pgsql:host=127.0.0.1;dbname=test',
    'username'      => 'postgres',
    'password'      => '',
    'options'       => array()
);

/**
 * Sqlite
 */
$GLOBALS['Aura\Sql_Schema\Setup\SqliteSetup']['pdo_params'] = array(
    'dsn'           => 'sqlite::memory:',
    'username'      => null,
    'password'      => null,
    'options'       => array()
);

/**
 * Sqlsrv
 */
$GLOBALS['Aura\Sql_Schema\Setup\SqlsrvSetup']['pdo_params'] = array(
    'dsn'           => 'sqlsrv:Server=localhost\\SQLEXPRESS;Database=test',
    'username'      => null,
    'password'      => null,
    'options'       => array()
);
