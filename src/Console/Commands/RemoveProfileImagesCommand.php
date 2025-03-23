<?php
namespace Console\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Console\Helpers\ProfileImageDir;

/**
 * Run this after performing the migrate:refresh command to delete all 
 * existing profile images.  May need sudo privileges. 
 */
class RemoveProfileImagesCommand extends Command
{
    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('tools:rm-profile-images')
            ->setDescription('Removes all profile images.')
            ->setHelp('Might need to use sudo on linux/mac -> sudo php console tools:rm-profile-images.');
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
        return ProfileImageDir::rmdirProfileImageDirectories();
    }
}