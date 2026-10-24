<?php
declare(strict_types=1);
namespace Tests\Unit;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Core\Lib\Testing\InteractsWithTime;
use Core\Lib\Utilities\DateFactory;
use Core\Lib\Utilities\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the InteractsWithTime trait and Wormhole.
 *
 * Strings passed to travelTo() are UTC.  The app timezone is
 * America/New_York, so DateFactory::now() reports New York time.
 */
class InteractsWithTimeTest extends TestCase {
    use InteractsWithTime;

    protected function setUp(): void {
        parent::setUp();
        $_ENV['TIME_ZONE'] = 'America/New_York';
        $_ENV['LOCALE'] = 'en';
    }

    // ---- freezeTime / freezeSecond -----------------------------------------

    public function test_freeze_time_stops_the_clock(): void {
        $frozen = $this->freezeTime();
        usleep(1000);

        $this->assertInstanceOf(CarbonInterface::class, $frozen);
        $this->assertTrue(DateFactory::now()->eq($frozen));
    }

    public function test_freeze_time_with_callback_returns_result_and_restores_clock(): void {
        $result = $this->freezeTime(fn(CarbonInterface $now) => $now->year);

        $this->assertSame(DateFactory::now()->year, $result);
        $this->assertNull(Carbon::getTestNow());
    }

    public function test_freeze_second_drops_microseconds(): void {
        $frozen = $this->freezeSecond();
        $this->assertSame(0, $frozen->micro);
        $this->assertSame(0, DateFactory::now()->micro);
    }

    // ---- travelTo ----------------------------------------------------------

    public function test_travel_to_interprets_strings_as_utc(): void {
        $this->travelTo('2026-03-15 14:30:00');

        $this->assertSame('2026-03-15 14:30:00', DateTime::timeStamps());
        $this->assertSame('10:30', DateFactory::now()->format('H:i'));
    }

    public function test_travel_to_accepts_timestamps_and_datetime(): void {
        $this->travelTo(1773585000);
        $this->assertSame('2026-03-15 14:30:00', DateTime::timeStamps());

        $this->travelTo(new \DateTimeImmutable('2026-07-04 12:00:00', new \DateTimeZone('UTC')));
        $this->assertSame('2026-07-04 12:00:00', DateTime::timeStamps());
    }

    public function test_travel_to_callback_restores_previously_frozen_time(): void {
        $this->travelTo('2026-03-15 14:30:00');

        $inside = $this->travelTo('2030-01-01 00:00:00', fn() => DateTime::timeStamps());

        $this->assertSame('2030-01-01 00:00:00', $inside);
        $this->assertSame('2026-03-15 14:30:00', DateTime::timeStamps());
    }

    public function test_travel_to_callback_restores_time_when_callback_throws(): void {
        $this->travelTo('2026-03-15 14:30:00');

        try {
            $this->travelTo('2030-01-01 00:00:00', function() {
                throw new \RuntimeException('boom');
            });
            $this->fail('Exception was not rethrown.');
        } catch (\RuntimeException) {
            // expected
        }

        $this->assertSame('2026-03-15 14:30:00', DateTime::timeStamps());
    }

    // ---- travel() / Wormhole -----------------------------------------------

    public function test_travel_moves_forward_from_frozen_time(): void {
        $this->travelTo('2026-03-15 14:30:00');
        $this->travel(5)->minutes();

        $this->assertSame('2026-03-15 14:35:00', DateTime::timeStamps());
    }

    public function test_travel_accepts_negative_values(): void {
        $this->travelTo('2026-03-15 14:30:00');
        $this->travel(-2)->days();

        $this->assertSame('2026-03-13 14:30:00', DateTime::timeStamps());
    }

    public function test_travel_returns_new_instance_without_callback(): void {
        $this->travelTo('2026-03-15 14:30:00');
        $date = $this->travel(1)->hour();

        $this->assertSame('11:30', $date->format('H:i'));
    }

    public function test_travel_with_callback_restores_previous_time(): void {
        $this->travelTo('2026-03-15 14:30:00');

        $inside = $this->travel(1)->week(fn() => DateTime::timeStamps());

        $this->assertSame('2026-03-22 14:30:00', $inside);
        $this->assertSame('2026-03-15 14:30:00', DateTime::timeStamps());
    }

    public function test_travel_months_does_not_overflow(): void {
        $this->travelTo('2026-01-31 12:00:00');
        $this->travel(1)->month();

        $this->assertSame('2026-02-28', DateFactory::now()->format('Y-m-d'));
    }

    public function test_travel_years_does_not_overflow(): void {
        $this->travelTo('2028-02-29 12:00:00');
        $this->travel(1)->year();

        $this->assertSame('2029-02-28', DateFactory::now()->format('Y-m-d'));
    }

    // ---- Restoring the real clock ------------------------------------------

    public function test_travel_back_restores_real_clock(): void {
        $this->travelTo('2000-01-01 00:00:00');
        $now = $this->travelBack();

        $this->assertNull(Carbon::getTestNow());
        $this->assertSame(Carbon::now()->year, $now->year);
    }

    public function test_after_hook_restores_real_clock(): void {
        $this->travelTo('2000-01-01 00:00:00');
        $this->restoreRealTime();

        $this->assertNull(Carbon::getTestNow());
    }

    // ---- Integration with DateTime -----------------------------------------

    public function test_time_ago_follows_travel(): void {
        $this->travelTo('2026-03-15 14:30:00');
        $this->travel(3)->hours();

        $this->assertSame('3 hours ago', DateTime::timeAgo('2026-03-15 14:30:00'));
    }
}