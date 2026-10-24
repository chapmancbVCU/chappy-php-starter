import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import {
    DateTime, FORMAT_12_HOUR, FORMAT_DATE_ONLY, FORMAT_HUMAN_READABLE,
    formatPhp, formatTime, getDateDefaults, now, parseDate, setDateDefaults,
    timeAgo, timeDifference, timeStamps, today, toISO8601,
} from '@chappy/utils/datetime';

/**
 * Mirrors DateTimeTest.php.  Time is frozen at 2026-03-15 14:30:00 UTC:
 * 10:30 in America/New_York (EDT), 15:30 in Europe/Berlin (CET).
 */
const FROZEN_UTC = '2026-03-15 14:30:00';

beforeEach(() => {
    setDateDefaults({ timeZone: 'America/New_York', locale: 'en' });
    vi.useFakeTimers();
    vi.setSystemTime(new Date('2026-03-15T14:30:00Z'));
});

afterEach(() => {
    vi.useRealTimers();
});

describe('parseDate wire contract', () => {
    it('reads zoneless strings as UTC, not browser-local time', () => {
        expect(parseDate(FROZEN_UTC).toISOString()).toBe('2026-03-15T14:30:00.000Z');
    });

    it('reads ISO 8601 with Z or offset as an exact moment', () => {
        expect(parseDate('2026-03-15T14:30:00Z').toISOString()).toBe('2026-03-15T14:30:00.000Z');
        expect(parseDate('2026-03-15T10:30:00-04:00').toISOString()).toBe('2026-03-15T14:30:00.000Z');
    });

    it('anchors date-only strings in the display timezone', () => {
        // new Date('1990-07-04') is UTC midnight, which is July 3 in New York.
        expect(formatTime('1990-07-04', FORMAT_DATE_ONLY)).toBe('1990-07-04');
        expect(formatTime('1990-07-04', FORMAT_DATE_ONLY, { timeZone: 'Asia/Tokyo' })).toBe('1990-07-04');
        expect(formatTime('1990-07-04', FORMAT_DATE_ONLY, { timeZone: 'Pacific/Honolulu' })).toBe('1990-07-04');
    });

    it('treats numbers as Unix seconds', () => {
        expect(parseDate(1773585000).toISOString()).toBe('2026-03-15T14:30:00.000Z');
    });

    it('returns null for empty values', () => {
        for (const v of [null, undefined, '', '0000-00-00', '0000-00-00 00:00:00']) {
            expect(parseDate(v)).toBeNull();
        }
        expect(formatTime(null)).toBe('');
    });

    it('rejects ambiguous or invalid strings', () => {
        expect(() => parseDate('03/15/2026')).toThrow(RangeError);
        expect(() => parseDate('not a date')).toThrow(RangeError);
        expect(() => parseDate('2026-13-45T00:00:00Z')).toThrow(RangeError);
    });

    it('honors fromTimeZone for zoneless strings', () => {
        expect(parseDate('2026-03-15 10:30:00', { fromTimeZone: 'America/New_York' }).toISOString())
            .toBe('2026-03-15T14:30:00.000Z');
    });
});

describe('formatTime / formatPhp', () => {
    it('converts UTC to the display timezone', () => {
        expect(formatTime(FROZEN_UTC, 'H:i')).toBe('10:30');
        expect(formatTime(FROZEN_UTC, 'H:i', { timeZone: 'Europe/Berlin' })).toBe('15:30');
    });

    it('defaults to FORMAT_12_HOUR', () => {
        expect(formatTime(FROZEN_UTC)).toBe('2026-03-15 10:30:00 AM');
    });

    it('renders the human readable format', () => {
        expect(formatTime(FROZEN_UTC, FORMAT_HUMAN_READABLE)).toBe('Sunday, March 15, 2026 10:30 AM');
    });

    it('translates month and weekday names', () => {
        expect(formatTime(FROZEN_UTC, 'F', { locale: 'fr' })).toBe('mars');
        expect(formatTime(FROZEN_UTC, 'l', { locale: 'fr' })).toBe('dimanche');
    });

    it('supports offsets, ISO 8601, RFC 2822, and Unix time', () => {
        const d = parseDate(FROZEN_UTC);
        expect(formatPhp(d, 'P O T')).toBe('-04:00 -0400 EDT');
        expect(formatPhp(d, 'c')).toBe('2026-03-15T10:30:00-04:00');
        expect(formatPhp(d, 'r')).toBe('Sun, 15 Mar 2026 10:30:00 -0400');
        expect(formatPhp(d, 'U')).toBe('1773585000');
        expect(formatPhp(d, 'p', { timeZone: 'UTC' })).toBe('Z');
    });

    it('supports day, week, and year tokens', () => {
        const d = parseDate('2028-02-29 17:05:09');
        expect(formatPhp(d, 'd D j N S w z t L y g G h a', { timeZone: 'UTC' }))
            .toBe('29 Tue 29 2 th 2 59 29 1 28 5 17 05 pm');
        expect(formatPhp(parseDate('2026-03-01 12:00:00'), 'jS', { timeZone: 'UTC' })).toBe('1st');
        expect(formatPhp(parseDate('2026-03-12 12:00:00'), 'jS', { timeZone: 'UTC' })).toBe('12th');
    });

    it('treats backslash-escaped characters as literals', () => {
        expect(formatTime(FROZEN_UTC, '\\T\\o\\d\\a\\y \\i\\s l')).toBe('Today is Sunday');
    });

    it('handles DST: noon UTC on both sides of the spring change', () => {
        expect(formatTime('2026-03-07 17:00:00', 'H:i T')).toBe('12:00 EST');
        expect(formatTime('2026-03-08 17:00:00', 'H:i T')).toBe('13:00 EDT');
    });
});

describe('now / today / timeStamps (mirror DateTime.php)', () => {
    it('now respects an explicit timezone', () => {
        expect(now('H:i', { timeZone: 'Europe/Berlin' })).toBe('15:30');
    });

    it('now defaults to the app timezone and FORMAT_12_HOUR', () => {
        expect(now()).toBe('2026-03-15 10:30:00 AM');
    });

    it('today rolls over by timezone', () => {
        vi.setSystemTime(new Date('2026-03-15T23:30:00Z'));
        expect(today(FORMAT_DATE_ONLY, { timeZone: 'Asia/Tokyo' })).toBe('2026-03-16');
        expect(today('H:i', { timeZone: 'Asia/Tokyo' })).toBe('00:00');
    });

    it('timeStamps returns frozen UTC time', () => {
        expect(timeStamps()).toBe(FROZEN_UTC);
    });

    it('toISO8601 uses the display timezone offset', () => {
        expect(toISO8601(FROZEN_UTC)).toBe('2026-03-15T10:30:00-04:00');
    });
});

describe('timeAgo / timeDifference', () => {
    it('matches Carbon for past times', () => {
        expect(timeAgo('2026-03-15 12:30:00')).toBe('2 hours ago');
        expect(timeAgo('2026-03-15 12:30:00', { short: true })).toBe('2h ago');
    });

    it('truncates like Carbon instead of rounding', () => {
        expect(timeAgo('2026-03-15 12:31:00')).toBe('1 hour ago');
    });

    it('uses calendar months and years', () => {
        expect(timeAgo('2026-02-15 14:30:00')).toBe('1 month ago');
        expect(timeAgo('2026-03-01 14:30:00')).toBe('2 weeks ago');
        expect(timeAgo('2025-03-15 14:30:00')).toBe('1 year ago');
        expect(timeAgo('2025-03-15 14:31:00')).toBe('11 months ago');
    });

    it('accepts the ISO strings HasCasts produces', () => {
        expect(timeAgo('2026-03-15T14:00:00Z')).toBe('30 minutes ago');
    });

    it('describes future times', () => {
        expect(timeAgo('2026-03-15 16:30:00')).toBe('in 2 hours');
    });

    it('timeDifference matches the PHP docblock example', () => {
        expect(timeDifference('2025-03-09 08:00:00', '2025-03-09 15:30:45')).toBe('7 hours before');
        expect(timeDifference('2025-03-09 15:30:45', '2025-03-09 08:00:00')).toBe('7 hours after');
    });
});

describe('defaults and namespace', () => {
    it('validates timezones', () => {
        expect(() => setDateDefaults({ timeZone: 'Mars/Olympus' })).toThrow(RangeError);
        expect(getDateDefaults().timeZone).toBe('America/New_York');
    });

    it('exposes a DateTime namespace like PHP', () => {
        expect(DateTime.FORMAT_12_HOUR).toBe(FORMAT_12_HOUR);
        expect(DateTime.timeAgo('2026-03-15 12:30:00')).toBe('2 hours ago');
    });
});

describe('parity with Carbon output', () => {
    // Expected strings captured from DateTime.php (Carbon 3).
    it('matches Spanish AM/PM with plain spaces', () => {
        expect(formatTime(FROZEN_UTC, 'h:i A', { locale: 'es' })).toBe('10:30 a. m.');
    });

    it('matches French and German short relative time', () => {
        expect(timeAgo('2026-03-15 14:29:50', { locale: 'fr', short: true })).toBe('il y a 10 s');
        expect(timeAgo('2026-03-15 14:29:50', { locale: 'de', short: true })).toBe('vor 10 Sek.');
    });

    it('matches Carbon short English years', () => {
        expect(timeAgo('2025-03-15 14:30:00', { short: true })).toBe('1yr ago');
        expect(timeAgo('2023-06-01 00:00:00', { short: true })).toBe('2yrs ago');
    });

    it('matches long relative time in other languages', () => {
        expect(timeAgo('2026-03-15 12:30:00', { locale: 'fr' })).toBe('il y a 2 heures');
        expect(timeAgo('2026-03-15 12:30:00', { locale: 'de' })).toBe('vor 2 Stunden');
        expect(timeAgo('2026-03-15 12:30:00', { locale: 'es' })).toBe('hace 2 horas');
    });
});