<?php
namespace Aura\SqlSchema;

interface MigrationInterface
{
    public function up();
    public function down();
}
