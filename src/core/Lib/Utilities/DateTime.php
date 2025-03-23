<?php
namespace Core\Lib\Utilities;

use Carbon\Carbon;

/**
 * Supports ability to manipulate how time is displayed.  Most functions are 
 * wrappers for those found in the Carbon class.
 */
class DateTime {
    const FORMAT_12_HOUR = 'Y-m-d h:i:s A';
    const FORMAT_24_HOUR = 'Y-m-d H:i:s';
    const FORMAT_HUMAN_READABLE = 'l, F j, Y g:i A';
    const FORMAT_DATE_ONLY = 'Y-m-d';
    const FORMAT_TIME_ONLY_12H = 'h:i A';
    const FORMAT_TIME_ONLY_24H = 'H:i';
    const FORMAT_FRIENDLY_DATE = 'F j, Y';
    const FORMAT_DAY_DATE = 'l, M j';
    const FORMAT_ISO_8601 = 'c';
    const FORMAT_RFC_2822 = 'r';
    const FORMAT_SQL_DATETIME = 'Y-m-d H:i:s';

    /**
     * Returns string that describes time.  The results can be set using 
     * constants, locale, and timezone.
     *
     * @param string $time String in format Y-m-d H:i:s A using UTC.
     * @param string $format Set format with a default of FORMAT_12_HOUR.
     * @param string $locale Set locale with 'en' as the default value.
     * @param string $timeZone Override default timezone with 'UTC' as default value.
     * @return string The formatted time.
     */
    public static function formatTime(string $time, string $format = self::FORMAT_12_HOUR, string $locale = 'en', string $timezone = 'UTC'): string {
        $carbon = Carbon::parse($time, $timezone)->setTimezone(Env::get('TIME_ZONE'));
    
        // Temporarily set the locale for this instance only
        return $carbon->locale($locale)->translatedFormat($format);
    }

    /**
     * Accepts UTC time in format Y-m-d H:i:s and returns a string describing  
     * how much time has elapsed.
     * 
     * This function supports a short form with the following example:
     * DateTime::timeAgo($user->updated_at, 'en', 'UTC', true);
     * 
     * This will show something like 21m.
     *
     * @param string $time String in format Y-m-d H:i:s using UTC.
     * @param string $locale Set locale with 'en' as the default value.
     * @param string $timeZone Override default timezone with 'UTC' as default value.
     * @param bool $short Set to true to show short form time.
     * @return string The time represented using language describing time since last change.
     */
    public static function timeAgo(string $time, string $locale = 'en', string $timezone = 'UTC', bool $short = false): string {
        $carbon = Carbon::parse($time, $timezone)
            ->setTimezone(Env::get('TIME_ZONE'))
            ->locale($locale); // Set locale per instance
    
        return $short 
            ? $carbon->diffForHumans(null, false, true) // Short format
            : $carbon->diffForHumans(); // Default long format
    }
    
    /**
     * Shows the difference between two times.  An example is shown below:
     * DateTimeHelper::timeDifference('2025-03-09 08:00:00', '2025-03-09 15:30:45');
     * Output: "7 hours before"
     *
     * @param string $startTime String in format Y-m-d H:i:s using UTC.
     * @param string $endTime String in format Y-m-d H:i:s using UTC.
     * @param string $timezone Override default timezone with 'UTC' as default value.
     * @return string Show exact difference between two times.
     */
    public static function timeDifference(string $startTime, string $endTime, string $timezone = 'UTC'): string {
        $start = Carbon::parse($startTime, $timezone);
        $end = Carbon::parse($endTime, $timezone);
        return $start->diffForHumans($end);
    }
    
    /**
     * Generates a timestamp.
     *
     * @return string A timestamp in the format Y-m-d H:i:s UTC time.
     */
    public static function timeStamps() {
        $dt = new \DateTime("now", new \DateTimeZone("UTC"));
        return $dt->format('Y-m-d H:i:s');
    }

    /**
     * Converts to ISO 8610 format.
     *
     * @param string $time String in format Y-m-d H:i:s using UTC.
     * @param string $timezone Override default timezone with 'UTC' as default value.
     * @return string The time in ISO 8610 format.  Example output: 2025-03-09T15:30:45-05:00
     */
    public static function toISO8601(string $time, string $timezone = 'UTC'): string {
        return Carbon::parse($time, $timezone)->toIso8601String();
    }
}