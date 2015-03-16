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
 * Represents one column from a table.
 *
 * @package Aura.SqlSchema
 *
 * @property-read string      $name    the column name
 * @property-read string      $type    the column data type, as reported by the database
 * @property-read int|null    $size    the column size (if defined)
 * @property-read int|null    $scale   the number of decimal places for the column (if defined)
 * @property-read bool        $notnull true, if this column rejects NULL values (a NOT NULL constraint is present)
 * @property-read string|null $default the default value for the column (if defined)
 * @property-read bool        $autoinc true, if the value of this column will auto-increment on INSERT
 * @property-read bool        $primary true, if this column is (or is part of) the primary key
 *
 */
class Column
{
    /**
     *
     * The name of the column.
     *
     * @var string
     *
     */
    protected $name;

    /**
     *
     * The datatype of the column.
     *
     * @var string
     *
     */
    protected $type;

    /**
     *
     * The size of the column; typically, this is a number of bytes or
     * characters for the column as a whole.
     *
     * @var int
     *
     */
    protected $size;

    /**
     *
     * The scale of the column (i.e., the number of decimal places).
     *
     * @var int
     *
     */
    protected $scale;

    /**
     *
     * Is the column marked as `NOT NULL`?
     *
     * @var bool
     *
     */
    protected $notnull;

    /**
     *
     * The default value of the column.
     *
     * @var mixed
     *
     */
    protected $default;

    /**
     *
     * Is the column auto-incremented?
     *
     * @var bool
     *
     */
    protected $autoinc;

    /**
     *
     * Is the column part of the primary key?
     *
     * @var bool
     *
     */
    protected $primary;

    /**
     *
     * Constructor.
     *
     * @param string $name The name of the column.
     *
     * @param string $type The datatype of the column.
     *
     * @param int $size The size of the column.
     *
     * @param int $scale The scale of the column (i.e., the number of digits
     * after the decimal point).
     *
     * @param bool $notnull Is the column defined as NOT NULL (i.e.,
     * required) ?
     *
     * @param mixed $default The default value of the column.
     *
     * @param bool $autoinc Is the column auto-incremented?
     *
     * @param bool $primary Is the column part of the primary key?
     *
     */
    public function __construct(
        $name,
        $type,
        $size,
        $scale,
        $notnull,
        $default,
        $autoinc,
        $primary
    ) {
        $this->name     = $name;
        $this->type     = $type;
        $this->size     = $size;
        $this->scale    = $scale;
        $this->notnull  = (bool) $notnull;
        $this->default  = $default;
        $this->autoinc  = (bool) $autoinc;
        $this->primary  = (bool) $primary;
    }

    /**
     *
     * Returns property values.
     *
     * @param string $key The property name.
     *
     * @return mixed The property value.
     *
     */
    public function __get($key)
    {
        return $this->$key;
    }

    /**
     * Check if the property is defined with any value
     *
     * @param string $key The property name.
     *
     * @return bool
     */
    public function __isset($key)
    {
        return property_exists($this, $key);
    }

    /**
     *
     * Returns column object for var_export. If you use "var_export" here,
     * there is another issue here. Saying that we're exporting an instance
     * of the class 'foo\bar\BazClass'.
     * Here var_export will return something like:
     *     "foo\bar\BazClass::__set_state(...)".
     * But we expect something like:
     *     "\foo\bar\BazClass::__set_state(...)".
     * You can see it here: https://bugs.php.net/bug.php?id=52740.
     *
     * @param array $array Column property.
     *
     * @return object \Aura\SqlSchema\Column.
     *
     */
    public static function __set_state($array)
    {
        $column = new \Aura\SqlSchema\Column(
            $array['name'],
            $array['type'],
            $array['size'],
            $array['scale'],
            $array['notnull'],
            $array['default'],
            $array['autoinc'],
            $array['primary']
        );

        return $column;
    }
}
