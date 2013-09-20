<?php
namespace Aura\Sql_Schema_Bundle\Query;

use Aura\Sql_Schema_Bundle\ConnectionFactory;
use Aura\Sql_Schema_Bundle\Query\Factory as QueryFactory;
use Aura\Sql_Schema_Bundle\Assertions;

abstract class AbstractQueryTest extends \PHPUnit_Framework_TestCase
{
    use Assertions;
    
    protected $query_type;
    
    protected $query;

    protected $pdo;
    
    protected function setUp()
    {
        parent::setUp();
        $pdo_factory = new ConnectionFactory;
        $query_factory   = new QueryFactory;
        $this->connection   = $pdo_factory->newInstance('sqlite', ':memory:');
        $this->query     = $query_factory->newInstance(
            $this->query_type,
            $this->connection
        );
    }
    
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testGetConnection()
    {
        $pdo = $this->query->getConnection();
        $this->assertSame($this->connection, $pdo);
    }
    
    public function testSetAddGetBind()
    {
        $actual = $this->query->getBind();
        $this->assertSame([], $actual);
        
        $expect = ['foo' => 'bar', 'baz' => 'dib'];
        $this->query->setBind($expect);
        $actual = $this->query->getBind();
        $this->assertSame($expect, $actual);
        
        $this->query->addBind(['zim' => 'gir']);
        $expect = ['foo' => 'bar', 'baz' => 'dib', 'zim' => 'gir'];
        $actual = $this->query->getBind();
        $this->assertSame($expect, $actual);
    }
}
