<?php

use Illuminate\Database\Capsule\Manager;

$capsule = new Manager();
$config = config('database');
foreach ($config['connections'] as $key => $value) {
    $capsule->addConnection($value, $key === $config['default'] ? 'default' : $key);
}
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
