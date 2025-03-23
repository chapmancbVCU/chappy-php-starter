<?php
namespace Console\Commands;
 
use Console\Helpers\View;
use Console\Helpers\Tools;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generates a new layout.
 */
class MakeLayoutCommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('make:layout')
            ->setDescription('Generates a new layout')
            ->setHelp('php console make:layout <layout_name>')
            ->addArgument('layout-name', InputArgument::REQUIRED, 'Pass the name of the new layout')
            ->addOption(
                'menu',
                null,
                InputOption::VALUE_OPTIONAL,
                'Menu file associated with a layout',
                false)
            ->addOption(
                'menu-acl',
                null,
                InputOption::VALUE_OPTIONAL,
                'menu_acl json file for menus and layouts',
                false);
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
        // Get inputs
        $layoutName = $input->getArgument('layout-name');
        $menu = $input->getOption('menu');
        $menuAcl = $input->getOption('menu-acl');

        // Process menu-acl input
        if($menuAcl === false) {
            Tools::info('--menu-acl argument not set so we ignore operation', 'blue');
        } else if($menuAcl === null) {
            View::makeMenuAcl($layoutName);
        } else {
            Tools::info('--menu-acl does not accept an argument', 'debug', 'red');
        }

        // Process menu input
        if($menu === false) {
            Tools::info('--menu argument not set so we ignore operation', 'blue');
            return View::makeLayout($layoutName);
        }
        else if($menu === null) {
            View::makeMenu($layoutName);
            return View::makeLayout($layoutName, $layoutName);
        } else {
            Tools::info('--menu does not accept an argument', 'debug', 'red');
            return Command::FAILURE;
        }
    }
}
