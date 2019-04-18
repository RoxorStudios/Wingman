<?php

namespace Wingman\Services;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleService extends Application {


	/**
     * Write our logo before outputting the default values
     */
	public function doRunCommand(Command $command, InputInterface $input, OutputInterface $output)
	{
		$this->writeLogo($command, $output);

		parent::doRunCommand($command, $input, $output);
	}

	/**
	 * Write logo
	 */
	private function writeLogo(Command $command, OutputInterface $output)
	{
		if($command->getName() != 'list') return;

		$logo = file_get_contents(__DIR__.'/../../assets/logo.ascii');

		$output->writeln("\n" . $logo . "\n\n");
	}
	


}