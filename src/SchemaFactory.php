<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.Sql_Schema
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Sql_Schema;

use Aura\Sql\PdoInterface;

class SchemaFactory
{
    protected $map = array(
        'mysql'  => 'Aura\Sql_Schema\MysqlSchema',
        'pgsql'  => 'Aura\Sql_Schema\PgsqlSchema',
        'sqlite' => 'Aura\Sql_Schema\SqliteSchema',
        'sqlsrv' => 'Aura\Sql_Schema\SqlsrvSchema',
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
