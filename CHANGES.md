# CHANGELOG

## 2.0.4

Maintenance release: the package now has continuous integration, and a
release workflow, for the first time.

- The test suite was written against the PHPUnit 4/5 API
  (`PHPUnit_Framework_TestCase`, `setExpectedException()`), which PHPUnit 6
  removed, so it could not run on any supported PHP at all. It now runs on
  PHP 5.6 through 8.5: 20 tests, 38 assertions (12 skipped without MySQL/PostgreSQL servers).
- ADD: `phpunit/phpunit` and `yoast/phpunit-polyfills` to `require-dev`.
  The package had no test runner declared at all, so `composer install`
  produced nothing to run the tests with.
- ADD: a Continuous Integration workflow, and a Release workflow calling the
  shared `auraphp/bin` workflow.
- FIX: connection settings moved from `<php><var>` in `phpunit.xml.dist`,
  removed in PHPUnit 10, into the `phpunit.php` bootstrap, where they can
  also be overridden from the environment.
- FIX: an unreachable MySQL or PostgreSQL server now skips its tests
  instead of erroring. A loaded extension does not imply a running server.
- FIX: `Column::__set_state()` export test tolerates the leading backslash
  that PHP 8.2 added to `var_export()` output for class names.
- The suite runs on **PHP 5.6 through 8.5**, via
  [`yoast/phpunit-polyfills`][polyfills], which lets one set of tests run on
  PHPUnit 5.7 through 11. Tests use `set_up()` rather than `setUp(): void`,
  because the `void` return type is a parse error before PHP 7.1.
- `phpunit.xml.dist` is deliberately minimal, so that a single config file is
  valid for every PHPUnit version in that range.

[polyfills]: https://github.com/Yoast/PHPUnit-Polyfills

No library code changed. `require.php` is unchanged, so nothing changes for
consumers of this package.

## 2.0.3

This release fixes a bug so that autoincrement columns with NULL or NOT NULL in SQLite are recognized as autoincrementing.
