<?php
namespace Console\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Runs built-in PHP server.
 */
class ServeCommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('serve')
            ->setDescription('Starts built-in PHP server')
            ->setHelp('run php console serve and navigate to localhost:8000')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Host Address', 'localhost')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'Port number', 8000);
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
        $host = $input->getOption('host');
        $port = $input->getOption('port');

        $output->writeln("<info>Starting PHP development server at http://{$host}:{$port}</info>");
        $output->writeln("<info>Press Ctrl+C to stop the server.</info>");

        // Run PHP built-in server
        $command = sprintf('php -S %s:%s -t . server.php', escapeshellarg($host), escapeshellarg($port));
        passthru($command);

        return Command::SUCCESS;
    }
}
