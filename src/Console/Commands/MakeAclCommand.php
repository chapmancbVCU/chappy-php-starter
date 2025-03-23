<?php
namespace Console\Commands;
 
use Console\Helpers\View;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Supports ability to generate a menu_acl json file.
 */
class MakeAclCommand extends Command {
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('make:acl')
            ->setDescription('Generates a new menu_acl json file.')
            ->setHelp('php console make:acl <menu_acl_json_name>')
            ->addArgument('acl-name', InputArgument::REQUIRED, 'Pass the name for the new menu_acl json file');
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
        $menuName = $input->getArgument('acl-name');
        return View::makeMenuAcl($menuName);
    }
}
