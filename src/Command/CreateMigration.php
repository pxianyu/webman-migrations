<?php

namespace Eloquent\Migrations\Command;

use Illuminate\Database\Console\Migrations\TableGuesser;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateMigration extends AbstractCommand
{
    protected static $defaultName = 'migrate:create';
    protected MigrationCreator $creator;

    protected function configure()
    {
        $this
            ->setDescription('Create a new migration')
            ->addArgument('name', InputArgument::REQUIRED, 'The migration name')
            ->addOption('--create', null, InputOption::VALUE_REQUIRED, 'The table to create')
            ->addOption('--table', null, InputOption::VALUE_REQUIRED, 'The table to migrate')
            ->addOption('--path', null, InputOption::VALUE_REQUIRED, 'create file path')
            ->setHelp('Creates a new migration' . PHP_EOL);

        parent::configure();

        $this->creator = new MigrationCreator(new Filesystem(), __DIR__ . '/../../data/stubs');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);
        $name = Str::snake(trim($this->input->getArgument('name')));
        $table = $this->input->getOption('table');
        $create = $this->input->getOption('create') ?: false;
        $path = $this->input->getOption('path');
        if (! $table && is_string($create)) {
            $table = $create;
            $create = true;
        }
        if (! $table) {
            [$table, $create] = TableGuesser::guess($name);
        }
        $this->writeMigration($name, $table, $create, $path);

        return 0;
    }

    /**
     * Write the migration file to disk.
     *
     * @param string $name
     * @param string $table
     * @param bool $create
     * @throws \Exception
     */
    protected function writeMigration(string $name, string $table, bool $create, string $path)
    {
        $file = $this->creator->create(
            $name,
            $this->getMigrationPath().DIRECTORY_SEPARATOR.$path,
            $table,
            $create
        );
        $this->output->writeln("<info>Created Migration:</info> {$file}");
    }
}
