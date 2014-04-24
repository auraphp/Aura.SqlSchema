<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.SqlSchema
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\SqlSchema;

/**
 * 
 * SQLite schema discovery tools.
 * 
 * @package Aura.SqlSchema
 * 
 */
class SqliteSchema extends AbstractSchema
{
    /**
     * 
     * The string used for SQLite autoincrement data types.
     * 
     * This is SQLite version 3; version 2 is different.
     * 
     * @var string
     * 
     */
    protected $autoinc_string = 'INTEGER PRIMARY KEY AUTOINCREMENT';

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
    public function fetchTableList($schema = null)
    {
        if ($schema) {
            $cmd = "
                SELECT name FROM {$schema}.sqlite_master WHERE type = 'table'
                ORDER BY name
            ";
        } else {
            $cmd = "
                SELECT name FROM sqlite_master WHERE type = 'table'
                UNION ALL
                SELECT name FROM sqlite_temp_master WHERE type = 'table'
                ORDER BY name
            ";
        }

        return $this->pdoFetchCol($cmd);
    }

    /**
     * 
     * Describes the columns in a table.
     * 
     * @param string $spec Return the columns in this table. This may be just
     * a `table` name, or a `schema.table` name.
     * 
     * @return array An associative array where the key is the column name
     * and the value is a Column object.
     * 
     */
    public function fetchTableCols($spec)
    {
        list($schema, $table) = $this->getSchemaAndTable($spec);
        $create = $this->getCreateTable($schema, $table);
        $cols = array();
        $this->setRawCols($cols, $schema, $table, $create);
        $this->convertColsToObjects($cols, $create);
        return $cols;
    }

    protected function getSchemaAndTable($spec)
    {
        list($schema, $table) = $this->splitName($spec);

        // strip non-word characters to try and prevent SQL injections
        $table = preg_replace('/[^\w]/', '', $table);

        // is there a schema?
        if ($schema) {
            // sanitize and add a dot
            $schema = preg_replace('/[^\w]/', '', $schema) . '.';
        }

        return array($schema, $table);
    }

    protected function getCreateTable($schema, $table)
    {
        $cmd = "
            SELECT sql FROM {$schema}sqlite_master
            WHERE type = 'table' AND name = :table
        ";
        return $this->pdoFetchValue($cmd, array('table' => $table));
    }

    protected function setRawCols(&$cols, $schema, $table, $create)
    {
        $table = $this->quoteName($table);
        $raw_cols = $this->pdoFetchAll("PRAGMA {$schema}TABLE_INFO($table)");
        foreach ($raw_cols as $val) {
            $this->addColFromRaw($cols, $val, $create);
        }
    }

    protected function addColFromRaw(&$cols, $val, $create)
    {
        $name = $val['name'];
        list($type, $size, $scale) = $this->getTypeSizeScope($val['type']);

        // find autoincrement column in CREATE TABLE sql.
        $autoinc_find = str_replace(' ', '\s+', $this->autoinc_string);
        $find = "(\"$name\"|\'$name\'|`$name`|\[$name\]|\\b$name)"
              . "\s+$autoinc_find";

        $autoinc = preg_match(
            "/$find/Ui",
            $create,
            $matches
        );

        $default = null;
        if ($val['dflt_value'] && $val['dflt_value'] != 'NULL') {
            $default = trim($val['dflt_value'], "'");
        }

        $cols[$name] = array(
            'name'    => $name,
            'type'    => $type,
            'size'    => ($size  ? (int) $size  : null),
            'scale'   => ($scale ? (int) $scale : null),
            'default' => $default,
            'notnull' => (bool) ($val['notnull']),
            'primary' => (bool) ($val['pk'] == 1),
            'autoinc' => (bool) $autoinc,
        );
    }

    protected function convertColsToObjects(&$cols, $create)
    {
        $names = array_keys($cols);
        $last = count($names) - 1;

        // loop through each column and find out if its default is a keyword
        foreach ($names as $curr => $name) {
            $this->setColumnDefault($cols, $name, $curr, $last, $names, $create);
            $cols[$name] = $this->column_factory->newInstance(
                $cols[$name]['name'],
                $cols[$name]['type'],
                $cols[$name]['size'],
                $cols[$name]['scale'],
                $cols[$name]['notnull'],
                $cols[$name]['default'],
                $cols[$name]['autoinc'],
                $cols[$name]['primary']
            );
        }
    }

    protected function setColumnDefault(&$cols, $name, $curr, $last, $names, $create)
    {
        // For defaults using keywords, SQLite always reports the keyword
        // *value*, not the keyword itself (e.g., '2007-03-07' instead of
        // 'CURRENT_DATE').
        //
        // The allowed keywords are CURRENT_DATE, CURRENT_TIME, and
        // CURRENT_TIMESTAMP.
        //
        //   <http://www.sqlite.org/lang_createtable.html>
        //
        // Check the table-creation SQL for the default value to see if it's
        // a keyword and report 'null' in those cases.
        // get the list of column names

        if (! $cols[$name]['default']) {
            return;
        }

        // look for :curr_col :curr_type . DEFAULT CURRENT_(*)
        $find = $cols[$name]['name'] . '\s+'
              . $cols[$name]['type']
              . '.*\s+DEFAULT\s+CURRENT_';

        // if not at the end, don't look further than the next coldef
        if ($curr < $last) {
            $next = $names[$curr + 1];
            $find .= '.*' . $cols[$next]['name'] . '\s+'
                   . $cols[$next]['type'];
        }

        // is the default a keyword?
        preg_match("/$find/ims", $create, $matches);
        if (! empty($matches)) {
            $cols[$name]['default'] = null;
        }
    }
}
