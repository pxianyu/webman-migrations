<?php

namespace Eloquent\Migrations\Command;

use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FreshMigration extends AbstractCommand
{
    protected static $defaultName = 'migrate:fresh';

    protected function configure()
    {
        $this
            ->setDescription('Drop all tables and re-run all migrations')
            ->addOption('drop-views', null, InputOption::VALUE_NONE, 'Drop all tables and views')
            ->addOption('drop-types', null, InputOption::VALUE_NONE, 'Drop all tables and types (Postgres only)')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production')
            ->addOption('seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run')
            ->addOption('seeder', null, InputOption::VALUE_OPTIONAL, 'Which root seeder should be used')
            ->setHelp('Drop all tables and re-run all migrations' . PHP_EOL);

        parent::configure();
    }

    /**
     * @throws ExceptionInterface
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bootstrap($input, $output);

        if (! $this->confirmToProceed()) {
            return 1;
        }

        if ($this->input->getOption('drop-views')) {
            $this->dropAllViews();
            $this->output->writeln('<info>Dropped all views successfully.</info>');
        }

        $this->dropAllTables();
        $this->output->writeln('<info>Dropped all tables successfully.</info>');

        if ($this->input->getOption('drop-types')) {
            $this->dropAllTypes();
            $this->output->writeln('<info>Dropped all types successfully.</info>');
        }

        $this->call('migrate', array_filter([
            '--database' => $this->database,
            '--force' => true,
        ]));

        if ($this->input->getOption('seed')) {
            $this->call('seed:run', array_filter([
                '--database' => $this->database,
                '--seed' => $this->input->getOption('seeder'),
                '--force' => true,
            ]));
        }

        return 0;
    }

    /**
     * Drop all of the database tables.
     *
     * @return void
     */
    protected function dropAllTables(): void
    {
        $this->getDb()->connection($this->database)
            ->getSchemaBuilder()
            ->dropAllTables();
    }

    /**
     * Drop all database views.
     *
     * @return void
     */
    protected function dropAllViews(): void
    {
        $this->getDb()->connection($this->database)
                    ->getSchemaBuilder()
                    ->dropAllViews();
    }
    /**
     * Drop all database types.
     *
     * @return void
     */
    protected function dropAllTypes(): void
    {
        $this->getDb()->connection($this->database)
                    ->getSchemaBuilder()
                    ->dropAllTypes();
    }
}
