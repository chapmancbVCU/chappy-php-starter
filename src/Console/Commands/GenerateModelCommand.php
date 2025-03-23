<?php
namespace Console\Commands;
 
use Console\Helpers\Model;
use Console\Helpers\Tools;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Supports ability to generate new model class.
 */
class GenerateModelCommand extends Command
{
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('make:model')
            ->setDescription('Generates a new model file!')
            ->setHelp('Generates a new model file.')
            ->addArgument('modelname', InputArgument::REQUIRED, 'Pass the model\'s name.');
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
        $modelName = $input->getArgument('modelname');

        // Generate the Model class
        return Tools::writeFile(
            ROOT.DS.'app'.DS.'Models'.DS.$modelName.'.php',
            Model::makeModel($modelName),
            'Model'
        );
    }
}
