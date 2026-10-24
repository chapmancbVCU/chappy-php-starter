<?php
declare(strict_types=1);
namespace Tests\Unit;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Core\Lib\Utilities\DateFactory;
use Core\Lib\Utilities\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests for DateFactory and the DateTime presentation helpers.
 *
 * Time is frozen at 2026-03-15 14:30:00 UTC, which is:
 * - 10:30 in America/New_York (EDT, UTC-4)
 * - 15:30 in Europe/Berlin (CET, UTC+1)
 */
class DateTimeTest extends TestCase {
    private const FROZEN_UTC = '2026-03-15 14:30:00';

    protected function setUp(): void {
        parent::setUp();
        $this->setEnv('TIME_ZONE', 'America/New_York');
        $this->setEnv('LOCALE', 'en');
        Carbon::setTestNow(Carbon::parse(self::FROZEN_UTC, 'UTC'));
    }

    protected function tearDown(): void {
        Carbon::setTestNow();
        DateFactory::use(Carbon::class);
        parent::tearDown();
    }

    /**
     * Adjust this to match how Env::get() reads values in Chappy.
     */
    private function setEnv(string $key, string $value): void {
        $_ENV[$key] = $value;
        putenv("{$key}={$value}");
    }

    // ---- The original bug -------------------------------------------------

    public function test_now_respects_explicit_timezone_override(): void {
        $this->assertSame('15:30', DateTime::now('Europe/Berlin', 'H:i'));
    }

    public function test_today_respects_explicit_timezone_override(): void {
        // 23:30 UTC on the 15th is already the 16th in Tokyo.
        Carbon::setTestNow(Carbon::parse('2026-03-15 23:30:00', 'UTC'));
        $this->assertSame('2026-03-16', DateTime::today('Asia/Tokyo', DateTime::FORMAT_DATE_ONLY));
    }

    // ---- Defaults ----------------------------------------------------------

    public function test_now_defaults_to_app_timezone(): void {
        $this->assertSame('10:30', DateTime::now(null, 'H:i'));
    }

    public function test_now_defaults_to_12_hour_format(): void {
        $this->assertSame('2026-03-15 10:30:00 AM', DateTime::now());
    }

    // ---- UTC storage helpers respect frozen time ---------------------------

    public function test_time_stamps_returns_frozen_utc_time(): void {
        $this->assertSame(self::FROZEN_UTC, DateTime::timeStamps());
    }

    public function test_now_plus_seconds_uses_frozen_utc_time(): void {
        $this->assertSame('2026-03-15 14:31:30', DateTime::nowPlusSeconds(90));
    }

    // ---- Formatting and relative time --------------------------------------

    public function test_format_time_converts_utc_to_app_timezone(): void {
        $this->assertSame('10:30', DateTime::formatTime(self::FROZEN_UTC, 'H:i'));
    }

    public function test_format_time_applies_locale(): void {
        $this->assertSame('mars', DateTime::formatTime(self::FROZEN_UTC, 'F', 'fr'));
    }

    public function test_time_ago_uses_frozen_time(): void {
        $this->assertSame('2 hours ago', DateTime::timeAgo('2026-03-15 12:30:00'));
        $this->assertSame('2h ago', DateTime::timeAgo('2026-03-15 12:30:00', 'en', 'UTC', true));
    }

    // ---- DateFactory -------------------------------------------------------

    public function test_factory_returns_mutable_by_default(): void {
        $this->assertInstanceOf(Carbon::class, DateFactory::now());
    }

    public function test_factory_can_switch_to_immutable(): void {
        DateFactory::use(CarbonImmutable::class);
        $date = DateFactory::now();

        $this->assertInstanceOf(CarbonImmutable::class, $date);
        $date->addDay();
        $this->assertSame('2026-03-15', $date->format('Y-m-d'));
    }

    public function test_frozen_time_applies_to_immutable_instances(): void {
        DateFactory::use(CarbonImmutable::class);
        $this->assertSame(self::FROZEN_UTC, DateTime::timeStamps());
    }

    public function test_factory_rejects_non_carbon_class(): void {
        $this->expectException(\InvalidArgumentException::class);
        DateFactory::use(\DateTime::class);
    }

    public function test_parse_accepts_string_timestamp_and_datetime(): void {
        $expected = '2026-03-15 10:30:00';

        $this->assertSame($expected, DateFactory::parse(self::FROZEN_UTC)->format('Y-m-d H:i:s'));
        $this->assertSame($expected, DateFactory::parse(1773585000)->format('Y-m-d H:i:s'));
        $this->assertSame($expected, DateFactory::parse(
            new \DateTimeImmutable(self::FROZEN_UTC, new \DateTimeZone('UTC'))
        )->format('Y-m-d H:i:s'));
    }

    public function test_parse_returns_app_timezone(): void {
        $this->assertSame('America/New_York', DateFactory::parse(self::FROZEN_UTC)->getTimezone()->getName());
    }
}