<?php

use Illuminate\Database\Capsule\Manager;

$capsule = new Manager();
$capsule->addConnection(config('database.connections.mysql'));
return [
    'enable' => true,
    'default_environment' => 'development',
    'paths' => [
        "migrations" => "database/migrations",
        "seeds"      => "database/seeders"
    ],
    'migration_table' => 'migrations',
    'db' => $capsule->getDatabaseManager()
];
