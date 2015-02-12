<?php
namespace Aura\SqlSchema\Migration;

use Aura\SqlSchema\AbstractMigration;

class V003 extends AbstractMigration
{
    public function up()
    {
        $this->pdo->exec("CREATE TABLE v3table (name VARCHAR(50))");
    }

    public function down()
    {
        $this->pdo->exec("DROP TABLE v3table");
    }
}
