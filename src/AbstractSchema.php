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

use PDO;

/**
 * 
 * Abstract schema discovery tools.
 * 
 * @package Aura.Sql_Schema
 * 
 */
abstract class AbstractSchema implements SchemaInterface
{
    protected $quote_name_prefix = '`';
    
    protected $quote_name_suffix = '`';
    
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
     * A Pdo connection.
     * 
     * @var PDO
     * 
     */
    protected $pdo;
    
    /**
     * 
     * Constructor.
     * 
     * @param PDO $pdo A database connection.
     * 
     * @param ColumnFactory $column_factory A column object factory.
     * 
     */
    public function __construct(PDO $pdo, ColumnFactory $column_factory)
    {
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

        return array($type, $size, $scale);
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
            return array(null, $name);
        } else {
            return array(substr($name, 0, $pos), substr($name, $pos+1));
        }
    }

    /**
     * 
     * Quotes a single identifier name (table, table alias, table column, 
     * index, sequence).
     * 
     * If the name contains `' AS '`, this method will separately quote the
     * parts before and after the `' AS '`.
     * 
     * If the name contains a space, this method will separately quote the
     * parts before and after the space.
     * 
     * If the name contains a dot, this method will separately quote the
     * parts before and after the dot.
     * 
     * @param string $name The identifier name to quote.
     * 
     * @return string|array The quoted identifier name.
     * 
     * @see replaceName()
     * 
     */
    public function quoteName($name)
    {
        // remove extraneous spaces
        $name = trim($name);

        // "name"."name"
        $pos = strrpos($name, '.');
        if ($pos) {
            $one = $this->quoteName(substr($name, 0, $pos));
            $two = $this->quoteName(substr($name, $pos + 1));
            return "{$one}.{$two}";
        }

        // "name"
        return $this->quote_name_prefix . $name . $this->quote_name_suffix;
    }
    
    protected function pdoFetchAll($statement, array $values = array())
    {
        $sth = $this->pdo->prepare($statement);
        $sth->execute($values);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    protected function pdoFetchCol($statement, array $values = array())
    {
        $sth = $this->pdo->prepare($statement);
        $sth->execute($values);
        return $sth->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    
    protected function pdoFetchValue($statement, array $values = array())
    {
        $sth = $this->pdo->prepare($statement);
        $sth->execute($values);
        return $sth->fetchColumn(0);
    }
}
