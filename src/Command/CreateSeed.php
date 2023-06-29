<?php

namespace Eloquent\Migrations\Command;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;

class CreateSeed extends AbstractCommand
{
    protected static $defaultName = 'seed:create';
    protected DatabaseMigrationRepository $repository;

    protected function configure()
    {
        $this
            ->setDescription('Create a new seeder class')
            ->addArgument('name', InputArgument::REQUIRED, 'The seeder name')
            ->setHelp('Create a new seeder class' . PHP_EOL);

        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bootstrap($input, $output);
        $className = $this->getClassName();
        $path = $this->getSeederPath($className);

        $contents = file_get_contents(__DIR__ . '/../../data/Seeder.php.dist');
        if ($contents === false) {
            throw new RuntimeException('Cannot read template file...');
        }

        $contents = str_replace('{{ class }}', $className, $contents);

        $ret = file_put_contents($path, $contents);
        if ($ret === false) {
            throw new RuntimeException(sprintf(
                'Cannot write seed file: %s',
                $path
            ));
        }
        $output->writeln("<info>Seed created</info> $path");

        return 0;
    }

    protected function getClassName(): string
    {
        $className = (string) $this->input->getArgument('name');

        if (!preg_match('/^[A-Z][a-zA-Z0-9]*$/', $className)) {
            throw new InvalidArgumentException(sprintf(
                'This seeder name is not a valid PHP CamelCase Class name: %s',
                $className
            ));
        }

        return $className;
    }

    protected function getSeederPath(string $className): string
    {
        $path = $this->getSeedPath() . DIRECTORY_SEPARATOR . $className . '.php';

        if (is_file($path)) {
            throw new InvalidArgumentException(sprintf(
                'This seeder name is already taken: %s',
                $className
            ));
        }

        return $path;
    }
}
