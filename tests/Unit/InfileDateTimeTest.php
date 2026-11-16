<?php

namespace Tests\Unit;

use App\Services\InfileDateTime;
use Tests\TestCase;

class InfileDateTimeTest extends TestCase
{
    public function dates()
    {
        return [
            ['14/11/2026 08:03:00', '2026-11-14 08:03:00'],
            ['2026-11-14 08:03:00', '2026-11-14 08:03:00'],
            ['2026-11-14T08:03:00', '2026-11-14 08:03:00'],
            ['2026-11-14 08:03:00.123', '2026-11-14 08:03:00'],
            ['2026-11-14T14:03:00.000Z', '2026-11-14 08:03:00'],
            ['2026-11-14T14:03:00.123456789Z', '2026-11-14 08:03:00'],
            ['2026-11-14T08:03:00-06:00', '2026-11-14 08:03:00'],
            ['2026-11-14T08:03:00.123456-0600', '2026-11-14 08:03:00'],
            ['2026-11-14T02:03:00+00:00', '2026-11-13 20:03:00'],
            ['31/02/2026 08:03:00', null],
            ['2026-02-31T08:03:00Z', null],
            ['2026-11-14 25:03:00', null],
            ['2026-11-14 08:03:00 extra', null],
            ['tomorrow', null],
            ['', null],
            [null, null],
            [[], null],
        ];
    }

    /** @dataProvider dates */
    public function test_normalizes_provider_dates_without_relative_parsing_or_date_overflow($input, $expected)
    {
        config(['app.timezone' => 'America/El_Salvador']);
        $this->assertSame($expected, InfileDateTime::databaseValue($input));
    }
}
