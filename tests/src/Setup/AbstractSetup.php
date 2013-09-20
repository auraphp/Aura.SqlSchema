<?php
namespace Aura\Sql_Schema_Bundle\Setup;

use Aura\Sql\PdoFactory;

abstract class AbstractSetup
{
    protected $type;
    
    protected $pdo;
    
    protected $table = 'aura_test_table';
    
    protected $schema1 = 'aura_test_schema1';
    
    protected $schema2 = 'aura_test_schema2';
    
    protected $create_table;
    
    public function __construct()
    {
        $pdo_params = $GLOBALS[get_class($this)]['pdo_params'];
        
        $pdo_factory = new PdoFactory;
        
        $this->pdo = $pdo_factory->newInstance(
            $pdo_params['dsn'],
            $pdo_params['username'],
            $pdo_params['password'],
            $pdo_params['options'],
            $pdo_params['attributes']
        );
        
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
            $this->pdo->bindValues(['name' => $name]);
            $this->pdo->exec($stm);
        }
    }
}
