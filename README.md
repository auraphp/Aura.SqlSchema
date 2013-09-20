Aura.Sql.Schema
===============

This library provides facilities to read table names and table columns from a
database.


## Preliminaries

### Installation and Autoloading

This library is installable via Composer and is registered on Packagist at
<https://packagist.org/packages/aura/autoload>. Installing via Composer will
set up autoloading automatically.

Alternatively, download or clone this repository, then require or include its
_autoload.php_ file.

### Dependencies and PHP Version

Because this is an Aura bundle and not a library, it has dependencies on other
Aura libraries; these can be found in the _composer.json_ file. This bundle
requires PHP version 5.3 or later (as opposed to most other Aura packages,
which require PHP 5.4 or later).

### Tests

[![Build Status](https://travis-ci.org/auraphp/Aura.Sql.png?branch=aura.sql.schema)](https://travis-ci.org/auraphp/Aura.Autoload)

This library has 100% code coverage. To run the library tests, first install
[PHPUnit][], then go to the library _tests_ directory and issue `phpunit` at
the command line.

[PHPUnit]: http://phpunit.de/manual/

### PSR Compliance

This library attempts to comply to [PSR-1][], [PSR-2][], and [PSR-4][]. If you
notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md


## Getting Started

Instantiation
-------------

Instantiate a driver-specific schema object with a matching database
connection:

```php
<?php
use Aura\Sql\MysqlConnection;
use Aura\Sql\Schema\MysqlSchema;

$connection = new MysqlConnection(...);
$schema = new MysqlSchema($connection);
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

