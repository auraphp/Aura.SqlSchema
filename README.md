# Aura.Sql_Schema

Provides facilities to read table names and table columns from a database
using a [PDO](http://php.net/PDO) connection.

## Foreword

### Installation and Autoloading

This library is installable and autoloadable via Composer with the following
`require` element in your `composer.json` file:

    "require": {
        "aura/sql-schema": "dev-develop-2"
    }
    
Alternatively, download or clone this repository, then require or include its
_autoload.php_ file.

### Dependencies and PHP Version

As with all Aura libraries, this library has no userland dependencies. It
requires PHP version 5.3 or later.

### Tests

[![Build Status](https://travis-ci.org/auraphp/Aura.{PACKAGE}.png?branch=develop-2)](https://travis-ci.org/auraphp/Aura.Sql_Schema)

This library has 100% code coverage with [PHPUnit][]. To run the tests at the
command line, go to the _tests_ directory and issue `phpunit`.

[phpunit]: http://phpunit.de/manual/

### PSR Compliance

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md


## Getting Started

### Instantiation

Instantiate a driver-specific schema object with a matching database
connection:

```php
<?php
use Aura\Sql\Schema\MysqlSchema;

$pdo = new PDO(...);
$schema = new MysqlSchema($pdo);
?>
```


Retrieving Schema Information
-----------------------------

To get a list of tables in the database, issue `fetchTableList()`:

```php
<?php
$tables = $schema->fetchTableList();
foreach ($tables as $table) {
    echo $table . PHP_EOL;
}
?>
```

To get information about the columns in a table, issue `fetchTableCols()`:

```php
<?php
$cols = $schema->fetchTableCols('table_name');
foreach ($cols as $name => $col) {
    echo "Column $name is of type "
       . $col->type
       . " with a size of "
       . $col->size
       . PHP_EOL;
}
?>
```

Each column description is a `Column` object with the following properties:

- `name`: (string) The column name

- `type`: (string) The column data type.  Data types are as reported by the database.

- `size`: (int) The column size.

- `scale`: (int) The number of decimal places for the column, if any.

- `notnull`: (bool) Is the column marked as `NOT NULL`?

- `default`: (mixed) The default value for the column. Note that sometimes
  this will be `null` if the underlying database is going to set a timestamp
  automatically.

- `autoinc`: (bool) Is the column auto-incremented?

- `primary`: (bool) Is the column part of the primary key?

