<?php
namespace Console\Commands;
 
use Console\Helpers\Tools;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Performs the command for generating api-docs: 
 * 
 * php doctum.phar update doctum.php
 */
class APICommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('make:api')
            ->setDescription('Generates or updates api-docs')
            ->setHelp('run php console make:api to generate or update api-docs');
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
        // Get absolute path of src/api-docs
        $apiDocsPath = realpath(__DIR__ . '/../../../src/api-docs');

        if (!$apiDocsPath || !is_dir($apiDocsPath)) {
            Tools::info("Error: src/api-docs directory not found at $apiDocsPath");
            return Command::FAILURE;
        }

        // Run Doctum with absolute paths
        $command = "php $apiDocsPath/doctum.phar update $apiDocsPath/doctum.php";
        shell_exec($command);
        
        Tools::info("Doctum API docs generated at $apiDocsPath/views");
        return Command::SUCCESS;
    }
}
