<?php
namespace Console\Commands;

use Console\Helpers\CommandHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Undocumented class
 */
class MakeHelperCommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('make:command-helper')
            ->setDescription('Generates a class that supports multiple commands')
            ->setHelp('php console make:command-helper <helper_name>')
            ->addArgument('helper-name', InputArgument::REQUIRED, 'Pass the command helper\'s name');
    }

    /**
     * Executes the command
     *
     * @param InputInterface $input The input.
     * @param OutputInterface $output The output.
     * @return int A value that indicates success, invalid, or failure.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return CommandHelper::makeHelper($input);
    }
}
