<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\SqlSchema;

use Pdo;

use InvalidArgumentException;

/**
 *
 * A factory for schema objects.
 *
 * @package Aura.SqlSchema
 *
 */
class SchemaFactory
{
    protected $columnFactory;

    protected $schemas = [
        'Mysql' => 'Aura\SqlSchema\MysqlSchema',
        'Pgsql' => 'Aura\SqlSchema\PgsqlSchema',
        'Sqlite' => 'Aura\SqlSchema\SqliteSchema',
        'Sqlsrv' => 'Aura\SqlSchema\SqlsrvSchema'
    ];

    /**
     * __construct
     *
     * @param ColumnFactory $columnFactory column factory
     * @param array         $schemas       map names to schema classes
     *
     * @access public
     */
    public function __construct(ColumnFactory $columnFactory = null, array $schemas = null)
    {
        $this->columnFactory = $columnFactory ?: new ColumnFactory;
        if ($schemas) {
            $this->schemas = $schemas;
        }
    }

    /**
     * newSchema
     *
     * @param Pdo    $pdo  A database connection
     * @param string $type type of schema to create
     *
     * @return SchemaInterface
     *
     * @access public
     */
    public function newSchema(Pdo $pdo, $type = null)
    {
        $type = $type ?: $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $type = ucfirst(strtolower($type));

        if (! isset($this->schemas[$type])) {
            throw new InvalidArgumentException(
                "No class for '$type' schema"
            );
        }

        $class = $this->schemas[$type];

        return new $class($pdo, $this->columnFactory);
    }
}
