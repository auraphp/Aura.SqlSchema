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

/**
 * 
 * Abstract schema discovery tools.
 * 
 * @package Aura.Sql_Schema_Bundle
 * 
 */
abstract class AbstractSchema implements SchemaInterface
{
    /**
     * 
     * A ColumnFactory for returning column information.
     * 
     * @var ColumnFactory
     * 
     */
    protected $column_factory;

    /**
     * 
     * A Aura\Sql\Pdo connection.
     * 
     * @var PdoInterface
     * 
     */
    protected $pdo;
    
    /**
     * 
     * Constructor.
     * 
     * @param PdoInterface $pdo A database connection.
     * 
     * @param ColumnFactory $column_factory A column object factory.
     * 
     */
    public function __construct(
        PdoInterface $pdo,
        ColumnFactory $column_factory
    ) {
        $this->pdo = $pdo;
        $this->column_factory = $column_factory;
    }
    
    /**
     * 
     * Returns a list of tables in the database.
     * 
     * @param string $schema Optionally, pass a schema name to get the list
     * of tables in this schema.
     * 
     * @return array The list of tables in the database.
     * 
     */
    abstract public function fetchTableList($schema = null);

    /**
     * 
     * Returns an array of columns in a table.
     * 
     * @param string $spec Return the columns in this table. This may be just
     * a `table` name, or a `schema.table` name.
     * 
     * @return array An associative array where the key is the column name
     * and the value is a Column object.
     * 
     */
    abstract public function fetchTableCols($spec);

    /**
     * 
     * Returns the column factory object.
     * 
     * @return ColumnFactory
     * 
     */
    public function getColumnFactory()
    {
        return $this->column_factory;
    }

    /**
     * 
     * Given a column specification, parse into datatype, size, and 
     * decimal scale.
     * 
     * @param string $spec The column specification; for example,
     * "VARCHAR(255)" or "NUMERIC(10,2)".
     * 
     * @return array A sequential array of the column type, size, and scale.
     * 
     */
    protected function getTypeSizeScope($spec)
    {
        $spec  = strtolower($spec);
        $type  = null;
        $size  = null;
        $scale = null;

        // find the parens, if any
        $pos = strpos($spec, '(');
        if ($pos === false) {
            // no parens, so no size or scale
            $type = $spec;
        } else {
            // find the type first.
            $type = substr($spec, 0, $pos);

            // there were parens, so there's at least a size.
            // remove parens to get the size.
            $size = trim(substr($spec, $pos), '()');

            // a comma in the size indicates a scale.
            $pos = strpos($size, ',');
            if ($pos !== false) {
                $scale = substr($size, $pos + 1);
                $size  = substr($size, 0, $pos);
            }
        }

        return [$type, $size, $scale];
    }

    /**
     * 
     * Splits an identifier name into two parts, based on the location of the
     * first dot.
     * 
     * @param string $name The identifier name to be split.
     * 
     * @return array An array of two elements; element 0 is the parts before
     * the dot, and element 1 is the part after the dot. If there was no dot,
     * element 0 will be null and element 1 will be the name as given.
     * 
     */
    protected function splitName($name)
    {
        $pos = strpos($name, '.');
        if ($pos === false) {
            return [null, $name];
        } else {
            return [substr($name, 0, $pos), substr($name, $pos+1)];
        }
    }
}
