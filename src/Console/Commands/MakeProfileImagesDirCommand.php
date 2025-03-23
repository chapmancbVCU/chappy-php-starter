<?php
namespace Console\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Console\Helpers\ProfileImageDir;
 
/**
 * Used during project initialization for the purpose for creating a new 
 * profile images directory.
 */
class MakeProfileImagesDirCommand extends Command
{
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('init:mk-profile-images-dir')
            ->setDescription('Builds Profile Image Dir')
            ->setHelp('Builds Profile Image Directory.');
    }
 
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return ProfileImageDir::mkdirProfileImages();
    }
}