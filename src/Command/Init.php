<?php

namespace Eloquent\Migrations\Command;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class Init extends Command
{
	protected static $defaultName = 'migrateInit';

	protected function configure()
	{
		$this
			->setDescription('Initialize the project')
			->addArgument('path', InputArgument::REQUIRED, 'The path for the root directory of the project.')
			->setHelp('Initialize the project for Eloquent Migrations'.PHP_EOL);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
    {
		$this->createPath($input, $output);

		return 0;
	}

	protected function createPath(InputInterface $input, OutputInterface $output):string
	{
		$path = (string) $input->getArgument('path');

		if (is_dir($path)) {
			return $path;
		}

		if (!is_dir($path) && !mkdir($path)) {
			throw new InvalidArgumentException(sprintf(
				'Cannot create `%s` directory',
				$path
			));
		}

		if (!is_writable($path) || is_file($path)) {
			throw new InvalidArgumentException(sprintf(
				'The directory `%s` is not writable',
				$path
			));
		}

		$output->writeln("<info>created</info> $path");
		return $path;
	}
}
