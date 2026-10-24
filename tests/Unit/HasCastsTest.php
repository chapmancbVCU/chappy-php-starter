<?php
declare(strict_types=1);
namespace Tests\Unit;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Core\Lib\Testing\InteractsWithTime;
use Core\Lib\Utilities\DateTime;
use Core\Traits\HasCasts;
use PHPUnit\Framework\TestCase;

/**
 * App timezone is America/New_York (UTC-4 on 2026-03-15).
 */
class HasCastsTest extends TestCase {
    use InteractsWithTime;

    protected function setUp(): void {
        parent::setUp();
        $_ENV['TIME_ZONE'] = 'America/New_York';
        $_ENV['LOCALE'] = 'en';
        $this->travelTo('2026-03-15 18:30:00');
    }

    private function post(): CastsPost {
        $post = new CastsPost();
        $post->id = 1;
        $post->title = 'Hello';
        $post->created_at = '2026-03-15 14:30:00';
        $post->birthday = '1990-07-04';
        return $post;
    }

    // ---- date() accessor ---------------------------------------------------

    public function test_date_returns_carbon_in_app_timezone(): void {
        $date = $this->post()->date('created_at');

        $this->assertInstanceOf(Carbon::class, $date);
        $this->assertSame('America/New_York', $date->getTimezone()->getName());
        $this->assertSame('2026-03-15 10:30:00', $date->format('Y-m-d H:i:s'));
    }

    public function test_date_works_for_undeclared_attributes(): void {
        $post = $this->post();
        $post->updated_at = '2026-03-15 16:00:00';

        $this->assertSame('12:00', $post->date('updated_at')->format('H:i'));
    }

    public function test_date_cast_is_not_shifted_across_timezones(): void {
        // Reading 1990-07-04 as UTC midnight would give July 3 in New York.
        $birthday = $this->post()->date('birthday');

        $this->assertSame('1990-07-04 00:00:00', $birthday->format('Y-m-d H:i:s'));
        $this->assertSame('America/New_York', $birthday->getTimezone()->getName());
    }

    public function test_date_returns_null_for_missing_or_empty_values(): void {
        $post = $this->post();
        $post->created_at = null;
        $this->assertNull($post->date('created_at'));

        $post->created_at = '0000-00-00 00:00:00';
        $this->assertNull($post->date('created_at'));

        $this->assertNull($post->date('deleted_at'));
        $this->assertNull((new CastsPost())->date('created_at'));
    }

    public function test_immutable_cast_returns_immutable_instance(): void {
        $event = new CastsEvent();
        $event->published_at = '2026-03-15 14:30:00';

        $this->assertInstanceOf(CarbonImmutable::class, $event->date('published_at'));
    }

    public function test_date_does_not_change_the_stored_property(): void {
        $post = $this->post();
        $post->date('created_at')->addYear();

        $this->assertSame('2026-03-15 14:30:00', $post->created_at);
    }

    // ---- toArray / json_encode ---------------------------------------------

    public function test_to_array_serializes_datetime_as_iso_8601_utc(): void {
        $array = $this->post()->toArray();

        $this->assertSame('2026-03-15T14:30:00Z', $array['created_at']);
    }

    public function test_to_array_serializes_date_cast_as_calendar_date(): void {
        $this->assertSame('1990-07-04', $this->post()->toArray()['birthday']);
    }

    public function test_to_array_excludes_protected_properties(): void {
        $array = $this->post()->toArray();

        $this->assertFalse(array_key_exists('secret', $array));
        $this->assertFalse(array_key_exists('casts', $array));
        $this->assertSame(['id', 'title', 'created_at', 'birthday'], array_keys($array));
    }

    public function test_to_array_includes_dynamic_properties(): void {
        $post = $this->post();
        $post->views = 42;

        $this->assertSame(42, $post->toArray()['views']);
    }

    public function test_to_array_keeps_null_dates_null(): void {
        $post = $this->post();
        $post->created_at = null;

        $this->assertNull($post->toArray()['created_at']);
    }

    public function test_custom_format_renders_in_app_timezone(): void {
        $event = new CastsEvent();
        $event->starts_at = '2026-03-15 14:30:00';

        $this->assertSame('2026-03-15 10:30', $event->toArray()['starts_at']);
    }

    public function test_json_encode_uses_casts(): void {
        $json = json_encode($this->post());

        $this->assertSame(
            '{"id":1,"title":"Hello","created_at":"2026-03-15T14:30:00Z","birthday":"1990-07-04"}',
            $json
        );
    }

    public function test_json_output_unchanged_for_models_without_casts(): void {
        // Matches what json_encode produced before HasCasts existed.
        $this->assertSame('{"id":7,"created_at":"2026-03-15 14:30:00"}', json_encode(new CastsPlain()));
    }

    // ---- Validation --------------------------------------------------------

    public function test_unsupported_cast_type_throws(): void {
        $post = new class extends CastsPost {
            protected array $casts = ['created_at' => 'datetme'];
        };
        $post->created_at = '2026-03-15 14:30:00';

        $this->expectException(\InvalidArgumentException::class);
        $post->toArray();
    }

    // ---- DateTime helpers accept date objects ------------------------------

    public function test_time_ago_accepts_cast_value(): void {
        $this->assertSame('4 hours ago', DateTime::timeAgo($this->post()->date('created_at')));
    }

    public function test_format_time_does_not_reshift_a_carbon_in_app_timezone(): void {
        // A New York Carbon must not be read as UTC and shifted again.
        $this->assertSame('10:30', DateTime::formatTime($this->post()->date('created_at'), 'H:i'));
    }

    public function test_to_iso8601_keeps_offset_of_date_object(): void {
        $this->assertSame('2026-03-15T10:30:00-04:00', DateTime::toISO8601($this->post()->date('created_at')));
    }

    public function test_time_difference_accepts_mixed_inputs(): void {
        $this->assertSame(
            '4 hours before',
            DateTime::timeDifference($this->post()->date('created_at'), '2026-03-15 18:30:00')
        );
    }
}

/**
 * Stand-in for a Chappy model.  Real models get HasCasts and
 * JsonSerializable from Core\Model; this keeps the test free of the DB.
 */
#[\AllowDynamicProperties]
class CastsPost implements \JsonSerializable {
    use HasCasts;

    public int $id;
    public ?string $title = null;
    public ?string $created_at = null;
    public ?string $birthday = null;
    protected string $secret = 'hidden';

    protected array $casts = [
        'created_at' => 'datetime',
        'birthday'   => 'date',
    ];
}

#[\AllowDynamicProperties]
class CastsEvent implements \JsonSerializable {
    use HasCasts;

    public ?string $starts_at = null;
    public ?string $published_at = null;
    public ?string $count = null;

    protected function casts(): array {
        return [
            'starts_at'    => 'datetime:Y-m-d H:i',
            'published_at' => 'immutable_datetime',
        ];
    }
}

#[\AllowDynamicProperties]
class CastsPlain implements \JsonSerializable {
    use HasCasts;

    public int $id = 7;
    public ?string $created_at = '2026-03-15 14:30:00';
}