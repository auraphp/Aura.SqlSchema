<?php
namespace Aura\SqlSchema;

use PDO;

class MigratorTest extends \PHPUnit_Framework_TestCase
{
    protected $pdo;

    protected $output = array();

    protected $migrator;

    public function setUp()
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec('CREATE TABLE schema_migration (version INT)');
        $pdo->exec('INSERT INTO schema_migration (version) VALUES (0)');

        $factories = array(
            function () use ($pdo) { return new Migration\V001($pdo); },
            function () use ($pdo) { return new Migration\V002($pdo); },
            function () use ($pdo) { return new Migration\V003($pdo); },
        );

        $migration_locator = new MigrationLocator($factories);
        $output_callable = array($this, 'captureOutput');

        $this->migrator = new Migrator($pdo, $migration_locator, $output_callable);
    }

    public function captureOutput($message)
    {
        $this->output[] = $message;
    }

    public function testUpAndDown()
    {
        $this->migrator->up(3);
        $this->migrator->down(0);
        $expect = array (
            'Migrating up from 0 to 3.',
            'Migrated up to 1.',
            'Migrated up to 2.',
            'Migrated up to 3.',
            'Migration up from 0 to 3 committed!',
            'Migrating down from 3 to 0.',
            'Migrated down from 3.',
            'Migrated down from 2.',
            'Migrated down from 1.',
            'Migration down from 3 to 0 committed!',
        );

        $this->assertSame($expect, $this->output);
    }

    public function testMigrateByOne()
    {
        $this->migrator->up();
        $this->migrator->up();
        $this->migrator->up();
        $this->migrator->down();
        $this->migrator->down();
        $this->migrator->down();

        $expect = array (
            'Migrating up from 0 to 1.',
            'Migrated up to 1.',
            'Migration up from 0 to 1 committed!',
            'Migrating up from 1 to 2.',
            'Migrated up to 2.',
            'Migration up from 1 to 2 committed!',
            'Migrating up from 2 to 3.',
            'Migrated up to 3.',
            'Migration up from 2 to 3 committed!',
            'Migrating down from 3 to 2.',
            'Migrated down from 3.',
            'Migration down from 3 to 2 committed!',
            'Migrating down from 2 to 1.',
            'Migrated down from 2.',
            'Migration down from 2 to 1 committed!',
            'Migrating down from 1 to 0.',
            'Migrated down from 1.',
            'Migration down from 1 to 0 committed!',
        );

        $this->assertSame($expect, $this->output);
    }

    public function testPdoErrmode()
    {
        $pdo = new PDO('sqlite::memory:');
        $migration_locator = new MigrationLocator();
        $output_callable = array($this, 'captureOutput');

        $this->setExpectedException('Exception', "PDO must use ERRMODE_EXCEPTION for migrations.");
        $this->migrator = new Migrator($pdo, $migration_locator, $output_callable);
    }

    public function testUpWhenAlreadyPast()
    {
        $this->migrator->up(3);
        $this->migrator->up(1);
        var_export($this->output);
    }

    public function testDownWhenAlreadyPast()
    {
        $this->migrator->up(1);
        $this->migrator->down(3);
        var_export($this->output);
    }

    public function testRollbackOnException()
    {
        $this->migrator->up(4);
        $expect = array (
            'Migrating up from 0 to 4.',
            'Migrated up to 1.',
            'Migrated up to 2.',
            'Migrated up to 3.',
            'Migration up from 0 to 4 failed.',
            'Rolled back to version 0.',
        );
    }
}
