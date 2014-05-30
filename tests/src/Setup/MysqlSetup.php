<?php
namespace Aura\SqlSchema\Setup;

class MysqlSetup extends AbstractSetup
{
    protected $extension = 'pdo_mysql';

    protected $create_table = "CREATE TABLE aura_test_table (
         id                     INTEGER AUTO_INCREMENT PRIMARY KEY
        ,name                   VARCHAR(50) NOT NULL
        ,test_size_scale        NUMERIC(7,3)
        ,test_default_null      CHAR(3) DEFAULT NULL
        ,test_default_string    VARCHAR(7) DEFAULT 'string'
        ,test_default_number    NUMERIC(5) DEFAULT 12345
        ,test_default_ignore    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";

    protected function createSchemas()
    {
        $this->pdo->query("CREATE DATABASE aura_test_schema1");
        $this->pdo->query("CREATE DATABASE aura_test_schema2");
        $this->pdo->query("USE aura_test_schema1");
    }

    protected function dropSchemas()
    {
        $this->pdo->query("DROP DATABASE IF EXISTS aura_test_schema1");
        $this->pdo->query("DROP DATABASE IF EXISTS aura_test_schema2");
    }
}
