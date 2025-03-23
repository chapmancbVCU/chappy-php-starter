<?php
namespace Console\Commands;
 
use Console\Helpers\{Tools, View};
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Supports ability to create components.
 */
class MakeComponentCommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('make:component')
            ->setDescription('Generates a new component')
            ->setHelp('php console make:component <component_name>')
            ->addArgument('component-name', InputArgument::REQUIRED, 'Pass the name for the new component')

            // Configure form component
            ->addOption('form', null, InputOption::VALUE_NONE, 'Create a form component')
            ->addOption('form-method', null, InputOption::VALUE_OPTIONAL, 'Form method (default: POST)', 'post')
            ->addOption('enctype', null, InputOption::VALUE_OPTIONAL, 'Form enctype', '')

            // Configure card component
            ->addOption('card', null, InputOption::VALUE_NONE, 'Create a card component')

            // Configure table component
            ->addOption('table', null, InputOption::VALUE_NONE, 'Create a table component');
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
        $componentName = $input->getArgument('component-name');

        if($input->getOption('card')) {
            return View::makeCardComponent($componentName);
        } else if($input->getOption('form')) {
            return View::makeFormComponent(
                $componentName,
                strtolower($input->getOption('form-method') ?? 'post'),
                $input->getOption('enctype') ??  ''
            );
        } else if($input->getOption('table')) {
            return View::makeTableComponent($componentName);
        }

        Tools::info('No form type selected', 'debug', 'red');
        return Command::FAILURE;
    }
}
