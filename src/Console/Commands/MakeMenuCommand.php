<?php
namespace Console\Commands;

use Console\Helpers\View;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Supports ability to generate a new menu file
 */
class MakeMenuCommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('make:menu')
            ->setDescription('Generates a new menu')
            ->setHelp('php console make:menu <menu_name>')
            ->addArgument('menu-name', InputArgument::REQUIRED, 'Pass the name for the new menu');
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
        $menuName = $input->getArgument('menu-name');
        return View::makeMenu($menuName);
    }
}
