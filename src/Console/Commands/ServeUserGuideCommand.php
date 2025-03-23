<?php
namespace Console\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Performs the command for serving the Jekyll user guide locally.
 */
class ServeUserGuideCommand extends Command {
    /**
     * Configures the command.
     */
    protected function configure(): void
    {
        $this
            ->setName('serve:docs')
            ->setDescription('Serves the user guide locally using Jekyll')
            ->setHelp('Run php console serve:docs --host=127.0.0.1 --port=4000 to serve the user guide')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Host address', '127.0.0.1')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'Port number', 4000);
    }

    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host = $input->getOption('host') ?: '127.0.0.1';
        $port = (int) $input->getOption('port') ?: 4000;

        // Change to the `docs` directory and serve the Jekyll site with specified host and port
        $command = sprintf('cd docs && bundle exec jekyll serve --host=%s --port=%d', escapeshellarg($host), $port);

        $output->writeln("<info>Starting Jekyll server at http://{$host}:{$port}</info>");
        $output->writeln("<info>Press Ctrl+C to stop the server.</info>");

        // Execute command and capture output
        $process = popen($command, 'r');

        if (!$process) {
            $output->writeln('<error>Failed to start Jekyll server</error>');
            return Command::FAILURE;
        }

        // Stream output to console
        while (!feof($process)) {
            $line = fgets($process);
            if ($line !== false) {
                $output->writeln(trim($line));
            }
        }
        
        pclose($process);

        return Command::SUCCESS;
    }
}
