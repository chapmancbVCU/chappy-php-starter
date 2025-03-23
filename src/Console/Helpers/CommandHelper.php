<?php
namespace Console\Helpers;

use Console\Helpers\Tools;
use Core\Lib\Utilities\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Supports commands related to building console commands and associated 
 * helper classes.
 */
class CommandHelper {
    /**
     * Generates new class that extends Command.
     *
     * @param InputInterface $input The name of the Command child class.
     * @return int A value that indicates success, invalid, or failure.
     */
    public static function makeCommand(InputInterface $input): int {
        $commandName = $input->getArgument('command-name');
        $ext = ".php";
        $fullPath = ROOT.DS.'src'.DS.'Console'.DS.'Commands'.DS.$commandName.'Command'.$ext;
        $content = '<?php
namespace Console\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Undocumented class
 */
class '.$commandName.'Command extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        //
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
        //
    }
}
';
        if(!file_exists($fullPath)) {
            $resp = file_put_contents($fullPath, $content);
        } else {
            Tools::info('Command already exists', 'debug', 'red');
            return Command::FAILURE;
        }

        Tools::info('Command successfully created');
        return Command::SUCCESS;
    }

    /**
     * Generates new class that contains functions that support multiple 
     * console commands.
     *
     * @param InputInterface $input The name of the command helper class.
     * @return int A value that indicates success, invalid, or failure.
     */
    public static function makeHelper(InputInterface $input): int {
        $helperName = Str::ucfirst($input->getArgument('helper-name'));
        $ext = ".php";
        $fullPath = ROOT.DS.'src'.DS.'Console'.DS.'Helpers'.DS.$helperName.$ext;
        $content = '<?php
namespace Console\Helpers;

use Symfony\Component\Console\Command\Command;

/**
 * 
 */
class '. $helperName.' {

}
';

        if(!file_exists($fullPath)) {
            $resp = file_put_contents($fullPath, $content);
        } else {
            Tools::info('Command helper already exists', 'debug', 'red');
            return Command::FAILURE;
        }

        Tools::info('Command helper successfully created');
        return Command::SUCCESS;
    }
}