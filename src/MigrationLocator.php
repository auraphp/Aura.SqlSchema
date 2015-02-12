<?php
namespace Aura\SqlSchema;

use Exception;

class MigrationLocator
{
    protected $factories = array();

    protected $instances = array();

    public function __construct(array $factories = array())
    {
        $this->factories = $factories;
    }

    public function get($version)
    {
        $version -= 1;

        if (! isset($this->factories[$version])) {
            throw new Exception("Migration {$version} not found.");
        }

        if (! isset($this->instances[$version])) {
            $factory = $this->factories[$version];
            $this->instances[$version] = $factory();
        }

        return $this->instances[$version];
    }

    public function getLatestVersion()
    {
        return count($factories);
    }
}
