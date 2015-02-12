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
        $key = $version - 1;

        if (! isset($this->factories[$key])) {
            throw new Exception("Migration {$version} not found.");
        }

        if (! isset($this->instances[$key])) {
            $factory = $this->factories[$key];
            $this->instances[$key] = $factory();
        }

        return $this->instances[$key];
    }
}
