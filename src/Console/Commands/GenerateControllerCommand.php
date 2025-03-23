<?php
namespace Console\Commands;
 
use Console\Helpers\Tools;
use Core\Lib\Utilities\Str;
use Console\Helpers\Controller;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
/**
 * Supports ability to generate new controller class.
 */
class GenerateControllerCommand extends Command
{
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('make:controller')
            ->setDescription('Generates a new controller file!')
            ->setHelp('php console make:controller MyController, add --layout=<optional_layout_name> to set layout, and --resource to generate CRUD functions')
            ->addArgument('controllername', InputArgument::REQUIRED, 'Pass the controller\'s name.')
            ->addOption(
                'layout',
                null,
                InputOption::VALUE_OPTIONAL,
                'Layout for views associated with controller.',
                false)
            ->addOption(
                'resource',
                null,
                InputOption::VALUE_OPTIONAL,
                'Add CRUD functions',
                false
            );
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
        $controllerName = Str::ucfirst($input->getArgument('controllername'));
        
        // Test if --layout is properly set
        $layoutInput = $input->getOption('layout');
        if($layoutInput === false) {
            $layout = 'default';
        } else if ($layoutInput === null) {
            Tools::info('Please supply name of layout.', 'debug', 'red');
            return Command::FAILURE;
        } else {
            if($layoutInput === '') {
                Tools::info('Please supply name of layout.', 'debug', 'red');
                return Command::FAILURE;
            }
            $layout = strtolower($layoutInput);
        }
        
        // Test if --resource flag is set and generate appropriate version of file
        $resource = $input->getOption('resource');
        if($resource === false) {
            // No option
            $content = Controller::defaultTemplate($controllerName, $layout);
        } else if ($resource === null) {
            // Option with no argument
            $content = Controller::resourceTemplate($controllerName, $layout);
        } else {
            // Option with argument
            Tools::info('--resource does not accept a value.', 'debug', 'red');
            return Command::FAILURE;
        }

        // Generate Controller class
        return Tools::writeFile(
            ROOT.DS.'app'.DS.'Controllers'.DS.$controllerName.'Controller.php',
            $content,
            "Controller"
        );
    }  
}
