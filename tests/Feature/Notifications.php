<?php
namespace Tests\Feature;
use PHPUnit\Framework\TestCase;
use App\Notifications\DummyNotification;
use Core\Lib\Testing\ApplicationTestCase;
use Symfony\Component\Console\Application;
use Console\Commands\NotificationTestCommand;
use Symfony\Component\Console\Tester\CommandTester;
/**
 * Unit tests
 */
class Notifications extends TestCase {
    public function testFailsWhenClassNotFound(): void
    {
        $app = new Application();
        $app->add(new NotificationTestCommand());

        $command = $app->find('notification:test');
        $tester  = new CommandTester($command);
        $exit = $tester->execute([
            'notification-name' => 'DoesNotExist',
        ]);
        
        $this->assertSame(1, $exit, 'Exit code should be failure for unknown class');
        
        $out = strtolower($tester->getDisplay());

        $this->assertStringContainsString('does not exist', $out);
    }

    public function testDryRunWithShortNotificationNameUsesViaChannels(): void
    {
        $app = new Application();
        $app->add(new NotificationTestCommand());

        $command = $app->find('notification:test');
        $tester  = new CommandTester($command);

        $exit = $tester->execute([
            'notification-name' => 'DummyNotification', // resolves to App\Notifications\DummyNotification
            '--dry-run'         => true,
        ]);

        $this->assertSame(0, $exit, 'Dry-run should succeed');

        $out = $tester->getDisplay();

        // Expect the DRY-RUN line
        $this->assertStringContainsString('[DRY-RUN]', $out);
        $this->assertStringContainsString('DummyNotification', $out);

        // Should use via() channels (['log']) when --channels is omitted
        $outNoSpacesLower = strtolower(str_replace(' ', '', $out));
        $this->assertStringContainsString('via[log]', $outNoSpacesLower);

        // Payload echo
        $outLower = strtolower($out);
        $this->assertStringContainsString('"dry_run": true', $outLower);
        $this->assertStringContainsString('"level": "info"', $outLower);
    }

    public function testDryRunHonorsChannelsOverride(): void
    {
        $app = new Application();
        $app->add(new NotificationTestCommand());

        $command = $app->find('notification:test');
        $tester  = new CommandTester($command);

        $exit = $tester->execute([
            'notification-name' => 'DummyNotification',
            '--dry-run'         => true,
            '--channels'        => 'log,database',
            '--with'            => 'level:warning,tag:foo',
        ]);

        $this->assertSame(0, $exit);

        $out = $tester->getDisplay();
        $outNoSpacesLower = strtolower(str_replace(' ', '', $out));

        $this->assertStringContainsString('[dry-run]', strtolower($out));
        // Override channels reflected in output
        $this->assertStringContainsString('via[log,database]', $outNoSpacesLower);
        // Overrides appear in JSON echo
        $this->assertStringContainsString('"level": "warning"', strtolower($out));
        $this->assertStringContainsString('"tag": "foo"', strtolower($out));
    }
}
