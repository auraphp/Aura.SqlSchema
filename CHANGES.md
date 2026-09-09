# CHANGELOG

## 2.0.4

Maintenance release: the package now has continuous integration, and a
release workflow, for the first time.

- The test suite runs on PHP 8.2 through 8.4. It was written against the
  PHPUnit 4/5 API (`PHPUnit_Framework_TestCase`, `setExpectedException()`),
  which PHPUnit 6 removed, so it could not run on any supported PHP at all.
  It now targets PHPUnit 11: 20 tests, 38 assertions (12 skipped without MySQL/PostgreSQL servers).
- ADD: `phpunit/phpunit` to `require-dev`. The package had no test runner
  declared, so `composer install` produced nothing to run the tests with.
- ADD: a Continuous Integration workflow, and a Release workflow calling the
  shared `auraphp/bin` workflow.
- FIX: connection settings moved from `<php><var>` in `phpunit.xml.dist`,
  removed in PHPUnit 10, into the `phpunit.php` bootstrap, where they can
  also be overridden from the environment.
- FIX: an unreachable MySQL or PostgreSQL server now skips its tests
  instead of erroring. A loaded extension does not imply a running server.
- FIX: `Column::__set_state()` export test tolerates the leading backslash
  that PHP 8.2 added to `var_export()` output for class names.

No library code changed. `require.php` is unchanged, so nothing changes for
consumers of this package.

## 2.0.3

This release fixes a bug so that autoincrement columns with NULL or NOT NULL in SQLite are recognized as autoincrementing.
