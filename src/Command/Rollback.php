<?php

namespace Eloquent\Migrations\Command;

use Eloquent\Migrations\Migrations\Migrator;
use Illuminate\Console\OutputStyle;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Rollback extends AbstractCommand
{
    protected static $defaultName = 'migrate:rollback';

    /**
     * The migration creator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected \Illuminate\Database\Migrations\Migrator $migrator;

    /**
     * The migration repository
     *
     * @var DatabaseMigrationRepository
     */
    protected DatabaseMigrationRepository $repository;

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

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bootstrap($input, $output);
        $this->repository = new DatabaseMigrationRepository($this->getDb(), $this->getMigrationTable());
        $this->migrator = new Migrator($this->repository, $this->getDb(), new Filesystem());
        $this->migrator->setOutput($output);

        $this->migrator->setOutput(new OutputStyle($input, $output))
            ->rollback([$this->getMigrationPath()], [
                'pretend' => $this->input->getOption('dry-run'),
                'step' => (int)$this->input->getOption('step'),
                'migration' => $this->input->getOption('migration'),
            ]);

        return 0;
    }
}
