<?php

namespace Eloquent\Migrations\Command;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use RuntimeException;

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

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$path = $this->createPath($input, $output);
		$this->createConfig($path, $output);
		$this->createBin($output);

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

	protected function createConfig(string $path, OutputInterface $output)
	{
		$configfile = 'elmigrator.php';
		$contents = file_get_Contents(__DIR__ . '/../../data/'. $configfile . '.dist');

		if ($contents === false) {
			throw new RuntimeException('Could not find template for config file');
		}

		$outputPath = $path . DIRECTORY_SEPARATOR . $configfile;
		$ret = file_put_contents($outputPath, $contents);
		if ($ret === false) {
			throw new RuntimeException(sprintf(
				'The config file `%s` could not be written',
				$configfile
			));
		}

		$output->writeln("<info>created</info> $outputPath");
	}
	
	public function createBin(OutputInterface $output)
	{
	    $pathBinFile = __DIR__ .'/../../bin/elmigrator';
	    
	    $contents = file_get_contents($pathBinFile);
	    
	    if ($contents === false) {
			throw new RuntimeException('Could not find bin file '. $pathBinFile);
		}
		
		$contents = str_replace("__DIR__ . '/../", "'vendor/hyde1/eloquent-migrations/", $contents); 
		
		$outputPathBinFile = getcwd() . DIRECTORY_SEPARATOR .'migrator';
		$ret = file_put_contents($outputPathBinFile, $contents);
		
		if ($ret === false) {
			throw new RuntimeException(sprintf(
				'Could not be written binary file to %s',
				$outputPathBinFile
			));
		}
		
		$output->writeln("<info>Created</info> $outputPathBinFile");
	}
}
