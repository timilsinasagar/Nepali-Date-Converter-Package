<?php

namespace Sagartimilsina\NepaliDate\Tests\Unit;

use Carbon\Carbon;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Sagartimilsina\NepaliDate\NepaliDate;

class NepaliDateManagerTest extends TestCase
{
    private NepaliDate $date;

    protected function setUp(): void
    {
        $this->date = new NepaliDate();
    }

    public function test_ad_to_bs_returns_a_rich_result(): void
    {
        $result = $this->date->adToBs('2026-07-08');

        $this->assertSame(2083, $result->year);
        $this->assertSame(3, $result->month);
        $this->assertSame(24, $result->day);
        $this->assertSame('Ashadh', $result->monthNameEn);
        $this->assertSame('असार', $result->monthNameNp);
        $this->assertSame('24 Ashadh 2083', $result->formattedEn);
        $this->assertSame('२४ असार २०८३', $result->formattedNp);
        $this->assertSame('2083-03-24', $result->toDateString());
        $this->assertInstanceOf(Carbon::class, $result->carbon);
        $this->assertSame('2026-07-08', $result->carbon->format('Y-m-d'));
    }

    /**
     * Weekday must come from the actual converted date, not a static
     * per-date table -- verified against several independent reference
     * points (see README "Data Accuracy").
     *
     * @dataProvider verifiedWeekdayProvider
     */
    public function test_weekday_is_derived_from_the_converted_date(string $bsDate, string $expectedWeekdayEn, string $expectedWeekdayNp): void
    {
        $adCarbon = $this->date->bsToAd($bsDate);
        $result = $this->date->adToBs($adCarbon);

        $this->assertSame($expectedWeekdayEn, $result->weekdayEn);
        $this->assertSame($expectedWeekdayNp, $result->weekdayNp);
    }

    public static function verifiedWeekdayProvider(): array
    {
        return [
            'Baisakh 1, 2073' => ['2073-01-01', 'Wednesday', 'बुधबार'],
            'Baisakh 1, 2080' => ['2080-01-01', 'Friday', 'शुक्रबार'],
            'Baisakh 1, 2081' => ['2081-01-01', 'Saturday', 'शनिबार'],
            'Baisakh 1, 2082' => ['2082-01-01', 'Monday', 'सोमबार'],
            'Baisakh 1, 2083' => ['2083-01-01', 'Tuesday', 'मंगलबार'],
        ];
    }

    public function test_bs_to_ad_returns_carbon(): void
    {
        $carbon = $this->date->bsToAd('2083-03-24');

        $this->assertInstanceOf(Carbon::class, $carbon);
        $this->assertSame('2026-07-08', $carbon->format('Y-m-d'));
    }

    public function test_to_carbon_is_an_alias_of_bs_to_ad(): void
    {
        $this->assertSame(
            $this->date->bsToAd('2083-03-24')->format('Y-m-d'),
            $this->date->toCarbon('2083-03-24')->format('Y-m-d'),
        );
    }

    public function test_ad_to_bs_accepts_a_carbon_instance_directly(): void
    {
        $carbon = Carbon::parse('2026-07-08');
        $result = $this->date->adToBs($carbon);

        $this->assertSame('2083-03-24', $result->toDateString());
    }

    public function test_round_trip_through_rich_api(): void
    {
        $bs = '2081-01-31';
        $roundTrip = $this->date->adToBs($this->date->bsToAd($bs));

        $this->assertSame($bs, $roundTrip->toDateString());
    }

    public function test_today_bs_and_ad_are_consistent(): void
    {
        $todayBs = $this->date->todayBS();
        $todayAd = $this->date->todayAD();

        $this->assertSame($todayAd->format('Y-m-d'), $todayBs->carbon->format('Y-m-d'));
    }

    public function test_now_ad_includes_current_time(): void
    {
        $before = Carbon::now();
        $now = $this->date->nowAD();

        $this->assertGreaterThanOrEqual($before->timestamp, $now->timestamp);
    }

    public function test_result_to_array_contains_expected_keys(): void
    {
        $array = $this->date->adToBs('2026-07-08')->toArray();

        foreach (['year', 'month', 'month_name_en', 'month_name_np', 'day', 'weekday_en', 'weekday_np', 'formatted_en', 'formatted_np', 'bs_date', 'bs_date_np', 'ad_date'] as $key) {
            $this->assertArrayHasKey($key, $array);
        }
    }

    public function test_result_json_serializes_to_the_same_array(): void
    {
        $result = $this->date->adToBs('2026-07-08');

        $this->assertSame($result->toArray(), $result->jsonSerialize());
    }

    public function test_invalid_bs_date_throws(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->date->bsToAd('not-a-date');
    }

    public function test_out_of_range_bs_year_throws(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->date->bsToAd('1999-01-01');
    }

    // --- Legacy v1.x string-based API preserved ---

    public function test_legacy_ad_to_bs_string_matches_v1_behavior(): void
    {
        $this->assertSame('2083-03-24', $this->date->adToBsString('2026-07-08'));
        $this->assertSame('२०८३-०३-२४', $this->date->adToBsString('2026-07-08', true));
    }

    public function test_legacy_bs_to_ad_string_matches_v1_behavior(): void
    {
        $this->assertSame('2026-07-08', $this->date->bsToAdString('2083-03-24'));
    }

    public function test_legacy_format_and_month_helpers_still_work(): void
    {
        $this->assertSame('24 Ashadh 2083', $this->date->format('2083-03-24'));
        $this->assertSame('Ashadh', $this->date->getMonth(3));
        $this->assertSame('असार', $this->date->getMonth(3, true));
        $this->assertSame('15', $this->date->getDay(15));
        $this->assertSame('Wednesday', $this->date->getDayName('2026-07-08'));
    }
}
