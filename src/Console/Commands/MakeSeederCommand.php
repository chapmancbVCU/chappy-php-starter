<?php
namespace Console\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Console\Helpers\DBSeeder;

/**
 * Undocumented class
 */
class MakeSeederCommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('make:seeder')
            ->setDescription('Generates a new Seeder class')
            ->setHelp('php console make:seeder ClassName')
            ->addArgument('seeder-name', InputArgument::REQUIRED, 'Pass the name of the seeder class you want to create');
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
        return DBSeeder::makeSeeder($input);
    }
}
