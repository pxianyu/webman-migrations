<?php

namespace Eloquent\Migrations\Migrations;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder;

abstract class Migration extends \Illuminate\Database\Migrations\Migration
{
    public Connection $db;

    protected function db(): Connection
    {
        return $this->db;
    }

    protected function schema(): Builder
    {
        return $this->db()->getSchemaBuilder();
    }

    abstract public function up(): void;
    abstract public function down(): void;
}
