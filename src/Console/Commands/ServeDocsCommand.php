<?php
namespace Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Runs built-in PHP server for serving Doctum API documentation.
 */
class ServeDocsCommand extends Command {
    /**
     * Configures the command.
     */
    protected function configure(): void
    {
        $this->setName('serve:api')
            ->setDescription('Starts built-in PHP server for API documentation')
            ->setHelp('Run php console serve:api and navigate to http://localhost:8001')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Host Address', 'localhost')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'Port number', 8001);
    }

    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host = $input->getOption('host');
        $port = $input->getOption('port');

        // Define the Doctum documentation directory
        $docsDir = 'src'.DS.'api-docs'.DS.'views';

        if (!is_dir($docsDir)) {
            $output->writeln("<error>Doctum documentation directory not found: $docsDir</error>");
            return Command::FAILURE;
        }

        $output->writeln("<info>Starting Doctum server at http://{$host}:{$port}</info>");
        $output->writeln("<info>Press Ctrl+C to stop the server.</info>");

        // Run PHP built-in server
        $command = sprintf('php -S %s:%s -t %s', escapeshellarg($host), escapeshellarg($port), escapeshellarg($docsDir));
        passthru($command);

        return Command::SUCCESS;
    }
}
