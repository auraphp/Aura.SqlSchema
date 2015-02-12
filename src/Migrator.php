<?php
namespace Aura\SqlSchema;

use Exception;
use PDO;

class Migrator
{
    protected $pdo;

    protected $migration_locator;

    protected $output_callable;

    protected $table;

    protected $col;

    public function __construct(
        PDO $pdo,
        MigrationLocator $migration_locator,
        $output_callable,
        $table = 'schema_migration',
        $col = 'version'
    ) {
        $this->pdo = $pdo;

        $errmode = $this->pdo->getAttribute(PDO::ATTR_ERRMODE);
        if ($errmode != PDO::ERRMODE_EXCEPTION) {
            throw new Exception("PDO must be use ERRMODE_EXCEPTION for migrations.");
        }

        $this->migration_locator = $migration_locator;
        $this->output_callable = $output_callable;
        $this->table = $table;
        $this->col = $col;
    }

    public function up($to = null)
    {
        $from = $this->fetchVersion();
        $to = $this->toVersion($to, $from, +1);
        if ($from >= $to) {
            $message = "Cannot migrate up to version {$to} "
                . "when already at or above it ({$from}).";
            throw new Exception($message);
        }
        $this->applyMigrations('up', $from, $to);
    }

    public function down($to = null)
    {
        $from = $this->fetchVersion();
        $to = $this->toVersion($to, $from, -1);
        if ($from <= $to) {
            $message = "Cannot migrate up down to version {$to} "
                . "when already at or below it ({$from}).";
            throw new Exception($message);
        }
        $this->applyMigrations('down', $from, $to);
    }

    protected function applyMigrations($direction, $from, $to)
    {
        $this->beginTransaction();
        $this->output("Migrating {$direction} from {$from} to {$to}.");
        $method = 'applyMigrations' . ucfirst($direction);
        $this->$method($from, $to);
        $this->updateVersion($to);
        $this->commit();
        $this->output("Migration {$direction} from {$from} to {$to} committed!");
    }

    protected function applyMigrationsUp($from, $to)
    {
        for ($version = $from + 1; $version <= $to; $version += 1) {
            $migration = $this->migration_locator->get($version);
            $this->applyMigration($migration, 'up', 'to', $version);
        }
    }

    public function applyMigrationsDown($from, $to)
    {
        for ($version = $from; $version > $to; $version -= 1) {
            $migration = $this->migration_locator->get($version);
            $this->applyMigration($migration, 'down', 'from', $version);
        }
    }

    protected function applyMigration(MigrationInterface $migration, $direction, $preposition, $version)
    {
        try {
            call_user_func(array($migration, $direction));
            $this->output("Migrated {$direction} {$preposition} {$version}.");
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $this->output("Failed to migrate {$direction} {$preposition} {$version}.");
            throw $e;
        }
    }

    protected function output($str)
    {
        call_user_func($this->output_callable, $str);
    }

    protected function toVersion($to, $from, $by)
    {
        if ($to === null) {
            $to = $from + $by;
        }
        return (int) $to;
    }

    protected function fetchVersion()
    {
        $stm = "SELECT {$this->col} FROM {$this->table}";
        $sth = $this->pdo->prepare($stm);
        $sth->execute();
        return (int) $sth->fetchColumn(0);
    }

    protected function updateVersion($version)
    {
        // fails if table not created, or if no row in there
        $stm = "UPDATE {$this->table} SET {$this->col} = {$version}";
        $sth = $this->pdo->prepare($stm);
        $sth->execute();
    }

    protected function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    protected function commit()
    {
        $this->pdo->commit();
    }

    protected function rollBack()
    {
        $this->pdo->rollBack();
    }
}
