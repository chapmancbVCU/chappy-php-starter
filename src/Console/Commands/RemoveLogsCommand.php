<?php
namespace Console\Commands;
 
use Console\Helpers\Log;
use Console\Helpers\Tools;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Supports ability to delete log file.
 */
class RemoveLogsCommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('log:clear')
            ->setDescription('Removes log file')
            ->setHelp('Run php console log:clear to remove log files.')

            // Remove app.log
            ->addOption('app', null, InputOption::VALUE_NONE, 'Delete app.log')

            // Remove cli.log
            ->addOption('cli', null, InputOption::VALUE_NONE, 'Delete cli.log')

            // Remove all logs
            ->addOption('all', null, InputOption::VALUE_NONE, 'Delete all logs');
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
        if($input->getOption('app')) {
            Log::delete(
                'app.log successfully cleared',
                ROOT.DS.'storage'.DS.'logs'.DS.'app.log'
            );
            return COMMAND::SUCCESS;
        } else if($input->getOption('cli')) {
            Log::delete(
                'cli.log successfully cleared',
                ROOT.DS.'storage'.DS.'logs'.DS.'cli.log'
            );
            return COMMAND::SUCCESS;
        } else if($input->getOption('all')) {
            Log::delete(
                'app.log successfully cleared',
                ROOT.DS.'storage'.DS.'logs'.DS.'app.log'
            );

            Log::delete(
                'cli.log successfully cleared',
                ROOT.DS.'storage'.DS.'logs'.DS.'cli.log'
            );
            return COMMAND::SUCCESS;
        }


        // Always execute when there is a failure.
        Tools::info('There was an issue removing the log file', 'debug', 'red');
        return COMMAND::FAILURE;
    }
}
