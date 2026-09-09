<?php
error_reporting(E_ALL);
require __DIR__ . '/autoload.php';

// Connection settings for the Setup classes, which read them from $GLOBALS.
// These used to live in phpunit.xml.dist as <php><var> elements; PHPUnit 10
// removed <var>, so they are defined here instead. Each can be overridden
// from the environment, which is how CI points the MySQL and PostgreSQL
// suites at service containers.
$defaults = array(
    'Aura_SqlSchema_Setup_MysqlSetup'  => array('mysql:host=127.0.0.1', 'root', ''),
    'Aura_SqlSchema_Setup_PgsqlSetup'  => array('pgsql:host=127.0.0.1;dbname=test', 'postgres', ''),
    'Aura_SqlSchema_Setup_SqliteSetup' => array('sqlite::memory:', '', ''),
    'Aura_SqlSchema_Setup_SqlsrvSetup' => array('sqlsrv:Server=localhost\\SQLEXPRESS;Database=test', '', ''),
);

foreach ($defaults as $key => $values) {
    list($dsn, $username, $password) = $values;
    $GLOBALS["{$key}__dsn"]      = getenv("{$key}__dsn") ?: $dsn;
    $GLOBALS["{$key}__username"] = getenv("{$key}__username") ?: $username;
    $GLOBALS["{$key}__password"] = getenv("{$key}__password") ?: $password;
}
