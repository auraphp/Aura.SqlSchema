<?php
/**
 * Mysql
 */
$GLOBALS['Aura\Sql_Schema_Bundle\Setup\MysqlSetup']['pdo_params'] = array(
    'dsn'           => 'mysql:host=127.0.0.1',
    'username'      => 'root',
    'password'      => '',
    'options'       => array(),
    'attributes'    => array(),
);

/**
 * Pgsql
 */
$GLOBALS['Aura\Sql_Schema_Bundle\Setup\PgsqlSetup']['pdo_params'] = array(
    'dsn'           => 'pgsql:host=127.0.0.1;dbname=test',
    'username'      => 'postgres',
    'password'      => '',
    'options'       => array(),
    'attributes'    => array(),
);

/**
 * Sqlite
 */
$GLOBALS['Aura\Sql_Schema_Bundle\Setup\SqliteSetup']['pdo_params'] = array(
    'dsn'           => 'sqlite::memory:',
    'username'      => null,
    'password'      => null,
    'options'       => array(),
    'attributes'    => array(),
);

/**
 * Sqlsrv
 */
$GLOBALS['Aura\Sql_Schema_Bundle\Setup\SqlsrvSetup']['pdo_params'] = array(
    'dsn'           => 'sqlsrv:Server=localhost\\SQLEXPRESS;Database=test',
    'username'      => null,
    'password'      => null,
    'options'       => array(),
    'attributes'    => array(),
);
