<?php
namespace Aura\SqlSchema\Setup;

use PDO;

abstract class AbstractSetup
{
    protected $pdo;

    protected $table = 'aura_test_table';

    protected $schema1 = 'aura_test_schema1';

    protected $schema2 = 'aura_test_schema2';

    protected $create_table;

    public function __construct()
    {
        $key = str_replace('\\', '_', get_class($this));
        $this->pdo = new PDO(
            $GLOBALS["{$key}__dsn"],
            $GLOBALS["{$key}__username"],
            $GLOBALS["{$key}__password"],
            array()
        );

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->dropSchemas();
        $this->createSchemas();
        $this->createTables();
        $this->fillTable();
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getSchema1()
    {
        return $this->schema1;
    }

    public function getSchema2()
    {
        return $this->schema2;
    }

    abstract protected function createSchemas();

    abstract protected function dropSchemas();

    protected function createTables()
    {
        // create in schema 1
        $this->pdo->query($this->create_table);

        // create again in schema 2
        $create_table2 = str_replace(
            $this->table,
            "{$this->schema2}.{$this->table}",
            $this->create_table
        );
        $this->pdo->query($create_table2);
    }

    // only fills in schema 1
    protected function fillTable()
    {
        $names = array(
            'Anna', 'Betty', 'Clara', 'Donna', 'Fiona',
            'Gertrude', 'Hanna', 'Ione', 'Julia', 'Kara',
        );

        $stm = "INSERT INTO {$this->table} (name) VALUES (:name)";
        foreach ($names as $name) {
            $sth = $this->pdo->prepare($stm);
            $sth->execute(array('name' => $name));
        }
    }
}
