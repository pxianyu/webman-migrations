<?php

namespace Eloquent\Migrations\Seeds;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;

abstract class Seeder
{    /**
     * Enables, if supported, wrapping the migration within a transaction.
     *
     * @var bool
     */
    public bool $withinTransaction = true;

    /**
     * Return array of Seeds that needs to be run before
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [];
    }

    /** @var Connection */
    private Connection $db;

    public function setDb(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @return Connection
     */
    protected function getDb(): Connection
    {
        return $this->db;
    }

    /**
     * @return Connection
     */
    protected function db(): Connection
    {
        return $this->db;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return get_class($this);
    }

    public function table(string $name): Builder
    {
        return $this->getDb()->table($name);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    abstract public function run(): void;
}
