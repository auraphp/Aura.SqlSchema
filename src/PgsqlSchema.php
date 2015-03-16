<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\SqlSchema;

/**
 *
 * PostgreSQL schema discovery tools.
 *
 * @package Aura.SqlSchema
 *
 */
class PgsqlSchema extends AbstractSchema
{
    /**
     *
     * Returns a list of all tables in the database.
     *
     * @param string $schema Fetch tbe list of tables in this schema;
     * when empty, uses the default schema.
     *
     * @return string[] All table names in the database.
     *
     */
    public function fetchTableList($schema = null)
    {
        if ($schema) {
            $cmd = "
                SELECT table_name
                FROM information_schema.tables
                WHERE table_schema = :schema
            ";
            $values = array('schema' => $schema);
        } else {
            $cmd = "
                SELECT table_schema || '.' || table_name
                FROM information_schema.tables
                WHERE table_schema != 'pg_catalog'
                AND table_schema != 'information_schema'
            ";
            $values = array();
        }

        return $this->pdoFetchCol($cmd, $values);
    }

    /**
     *
     * Returns an array of columns in a table.
     *
     * @param string $spec Return the columns in this table. This may be just
     * a `table` name, or a `schema.table` name.
     *
     * @return Column[] An associative array where the key is the column name
     * and the value is a Column object.
     *
     */
    public function fetchTableCols($spec)
    {
        list($schema, $table) = $this->splitName($spec);

        // modified from Zend_Db_Connection_Pdo_Pgsql
        $cmd = "
            SELECT
                a.attname AS name,
                FORMAT_TYPE(a.atttypid, a.atttypmod) AS type,
                a.attnotnull AS notnull,
                co.contype AS primary,
                d.adsrc AS default
            FROM pg_attribute AS a
            JOIN pg_class AS c ON a.attrelid = c.oid
            JOIN pg_namespace AS n ON c.relnamespace = n.oid
            JOIN pg_type AS t ON a.atttypid = t.oid
            LEFT OUTER JOIN pg_constraint AS co
                ON (co.conrelid = c.oid AND a.attnum = ANY(co.conkey) AND co.contype = 'p')
            LEFT OUTER JOIN pg_attrdef AS d
                ON (d.adrelid = c.oid AND d.adnum = a.attnum)
            WHERE a.attnum > 0 AND c.relname = :table
        ";

        $bind_values = array('table' => $table);

        if ($schema) {
            $cmd .= " AND n.nspname = :schema";
            $bind_values['schema'] = $schema;
        }

        $cmd .= "\n            ORDER BY a.attnum";

        // where the columns are stored
        $cols = array();

        // get the column descriptions
        $raw_cols = $this->pdoFetchAll($cmd, $bind_values);

        // loop through the result rows; each describes a column.
        foreach ($raw_cols as $val) {
            $name = $val['name'];
            list($type, $size, $scale) = $this->getTypeSizeScope($val['type']);
            $cols[$name] = $this->column_factory->newInstance(
                $name,
                $type,
                ($size  ? (int) $size  : null),
                ($scale ? (int) $scale : null),
                (bool) ($val['notnull']),
                $this->getDefault($val['default']),
                (bool) (substr($val['default'], 0, 7) == 'nextval'),
                (bool) ($val['primary'])
            );
        }

        // done
        return $cols;
    }

    /**
     *
     * Given a native column SQL default value, finds a PHP literal value.
     *
     * SQL NULLs are converted to PHP nulls.  Non-literal values (such as
     * keywords and functions) are also returned as null.
     *
     * @param string $default The column default SQL value.
     *
     * @return scalar A literal PHP value.
     *
     */
    protected function getDefault($default)
    {
        // numeric literal?
        if (is_numeric($default)) {
            return $default;
        }

        // string literal?
        $k = substr($default, 0, 1);
        if ($k == '"' || $k == "'") {
            // find the trailing :: typedef
            $pos = strrpos($default, '::');
            // also remove the leading and trailing quotes
            return substr($default, 1, $pos-2);
        }

        // null or non-literal
        return null;
    }
}
