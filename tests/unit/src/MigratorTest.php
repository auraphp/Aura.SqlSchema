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

    public function test()
    {
        $this->migrator->up(3);
        $this->migrator->down(0);
        var_dump($this->output);
    }
}
