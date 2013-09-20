<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.Sql_Schema_Bundle
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Sql_Schema_Bundle;

use Aura\Sql\PdoInterface;

class SchemaFactory
{
    protected $map = array(
        'mysql'  => 'Aura\Sql_Schema_Bundle\MysqlSchema',
        'pgsql'  => 'Aura\Sql_Schema_Bundle\PgsqlSchema',
        'sqlite' => 'Aura\Sql_Schema_Bundle\SqliteSchema',
        'sqlsrv' => 'Aura\Sql_Schema_Bundle\SqlsrvSchema',
    );
    
    public function __construct(array $map = array())
    {
        $this->map = array_merge($this->map, $map);
    }
    
    public function newInstance(PdoInterface $pdo)
    {
        $driver = $pdo->getDriver();
        $class = $this->map[$driver];
        return new $class($pdo, new ColumnFactory);
    }
}
