<?php
namespace Aura\SqlSchema\Migration;

use Aura\SqlSchema\AbstractMigration;

class V002 extends AbstractMigration
{
    public function up()
    {
        $this->pdo->exec("ALTER TABLE v1table RENAME TO v2table");
    }

    public function down()
    {
        $this->pdo->exec("ALTER TABLE v2table RENAME TO v1table");
    }
}
