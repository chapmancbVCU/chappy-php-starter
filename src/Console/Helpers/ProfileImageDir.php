<?php
namespace Console\Helpers;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Symfony\Component\Console\Command\Command;

/**
 * Contains functions for creating and deleting the profile images directory.
 */
class ProfileImageDir {
    private static $_path = ROOT.DS.'storage'.DS.'app'.DS.'private'.DS .'profile_images';

    /**
     * Performs mkdir operation on the profile images directory
     *
     * @return int A value that indicates success, invalid, or failure.
     */
    public static function mkdirProfileImages(): int {
        if(!file_exists(self::$_path)) {
            mkdir(self::$_path, 0775, true);
        } else {
            Tools::info('The profile_images directory already exists.', 'debug', 'red');
            return Command::FAILURE;
        }
        Tools::info('profile_images directory created');
        return Command::SUCCESS;
    }

    /**
     * Performs rmdir operation on the profile images directory.
     *
     * @return int A value that indicates success, invalid, or failure.
     */
    public static function rmdirProfileImageDirectories(): int {
        $it = new RecursiveDirectoryIterator(self::$_path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
                     RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                $response = rmdir($file->getPathname());
            } else {
                $response = unlink($file->getPathname());
            }
        }

        if($response == false) {
            Tools::info('Failure ocurred when deleting images.', 'debug', 'red');
            return Command::FAILURE;
        }
        Tools::info('All profile images have been deleted.');
        return Command::SUCCESS;
    }
}