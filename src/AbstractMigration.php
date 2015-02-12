<?php
namespace Aura\SqlSchema;

use PDO;

abstract class AbstractMigration implements MigrationInterface
{
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
