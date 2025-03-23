<?php
namespace Console\Helpers;
use Console\Helpers\Tools;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Supports unit test related console commands.
 */
class Test {
    /**
     * The template for a new TestCase class.
     *
     * @param string $testName The name of the TestCase class.
     * @return string The contents for the new TestCase class.
     */
    public static function makeTest(string $testName): string {
        return '<?php
namespace Tests;
use PHPUnit\Framework\TestCase;

/**
 * Undocumented class
 */
class '.$testName.' extends TestCase {
}
';
    }

    /**
     * Runs the unit test contained in the TestCase class
     *
     * @param InputInterface $input Input obtained from the console used to 
     * set name of unit test we want to run.
     * @param OutputInterface $output The results of the test.
     * @return int A value that indicates success, invalid, or failure.
     */
    public static function runTest(InputInterface $input, OutputInterface $output) {
        $testName = $input->getArgument('testname');
        $command = 'php vendor/bin/phpunit tests'.DS.$testName.'.php';
        $output->writeln(Tools::border());
        $output->writeln(sprintf('Running command: '.$command));
        $output->writeln(Tools::border());
        $output->writeln(shell_exec($command));
        $output->writeln(Tools::border());
        return Command::SUCCESS;
    }
}