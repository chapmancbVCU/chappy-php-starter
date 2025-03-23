<?php
namespace Console\Commands;

use Console\Helpers\View;
use Console\Helpers\Tools;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Implements command for making a new view file.
 */
class MakeViewCommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('make:view')
            ->setDescription('Generates a new view')
            ->setHelp('php console make:view <directory_name>.<view_name>')
            ->addArgument('view-name', InputArgument::REQUIRED, 'Pass name of directory and view');
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
        $viewArray = explode(".", $input->getArgument('view-name'));

        if (sizeof($viewArray) !== 2) {
            Tools::info(
                'Issue parsing argument. Make sure your input is in the format: <directory_name>.<view_name>',
                'debug',
                'red'
            );
            return Command::FAILURE;
        }

        $directory = ROOT . DS . 'resources' . DS . 'views' . DS . $viewArray[0];
        $filePath = $directory . DS . $viewArray[1].'.php';
        $helper = new QuestionHelper(); // <-- Manual instantiation to avoid `getHelper()` issues

        // Debug to check if helper exists
        if (!$helper) {
            Tools::info('Helper could not be instantiated.', 'debug', 'red');
            return Command::FAILURE;
        }

        // Check if directory exists
        if (!is_dir($directory)) {
            $question = new ConfirmationQuestion(
                "The directory '$directory' does not exist. Do you want to create it? (y/n) ", 
                false
            );

            if ($helper->ask($input, $output, $question)) {
                mkdir($directory, 0755, true);
                Tools::info("Directory created: $directory", 'blue');
            } else {
                Tools::info('Operation canceled.', 'debug', 'blue');
                return Command::FAILURE;
            }
        }

        return View::makeView($filePath);
    }
}
