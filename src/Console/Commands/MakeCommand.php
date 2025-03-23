<?php
namespace Console\Commands;

use Console\Helpers\CommandHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Supports ability to create new console command.
 */
class MakeCommand extends Command
{
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('make:command')
            ->setDescription('Generates a new command class')
            ->setHelp('php console make:command <test_name>')
            ->addArgument('command-name', InputArgument::REQUIRED, 'Pass the command\'s name.');
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
       return CommandHelper::makeCommand($input);
    }
}