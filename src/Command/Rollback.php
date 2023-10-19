<?php

namespace Eloquent\Migrations\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Eloquent\Migrations\Migrations\Migrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Composer;

class Rollback extends AbstractCommand
{
    protected static $defaultName = 'migrate:rollback';

    /**
     * The migration creator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * The migration repository
     *
     * @var DatabaseMigrationRepository
     */
    protected $repository;

    protected function configure()
    {
        $this
            ->setDescription('Rollback migrations')
            ->addOption('dry-run', 'x', InputOption::VALUE_NONE, 'Dump query to standard output instead of executing it')
            ->addOption('step', 's', InputOption::VALUE_REQUIRED, 'Number of migrations to rollback', 0)
            ->addOption('migration', 'm', InputOption::VALUE_REQUIRED, 'Migrate a specific migration')
            ->setHelp('Rollback the last migration' . PHP_EOL);

        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);
        $this->repository = new DatabaseMigrationRepository($this->getDb(), $this->getMigrationTable());
        $this->migrator = new Migrator($this->repository, $this->getDb(), new Filesystem());
        $this->migrator->setOutput($output);
        $this->migrator->usingConnection($this->database, function () {
            $this->migrator->rollback([$this->getMigrationPath()], [
                    'pretend' => $this->input->getOption('dry-run'),
                    'step' => (int)$this->input->getOption('step'),
                    'migration' => $this->input->getOption('migration'),
                ]);
        });

        return 0;
    }
}
