<?php

namespace Sagartimilsina\NepaliDate\Tests;

use PHPUnit\Framework\TestCase;
use Sagartimilsina\NepaliDate\NepaliDate;

class NepaliDateTest extends TestCase
{
    private NepaliDate $date;

    protected function setUp(): void
    {
        $this->date = new NepaliDate();
    }

    public function test_ad_to_bs_conversion(): void
    {
        $this->assertSame('2083-03-24', $this->date->adToBs('2026-07-08'));
    }

    public function test_bs_to_ad_conversion(): void
    {
        $this->assertSame('2026-07-08', $this->date->bsToAd('2083-03-24'));
    }

    public function test_round_trip_conversion(): void
    {
        $ad = '2025-01-15';
        $bs = $this->date->adToBs($ad);
        $this->assertSame($ad, $this->date->bsToAd($bs));
    }

    public function test_ad_to_bs_devanagari_output(): void
    {
        $this->assertSame('२०८३-०३-२४', $this->date->adToBs('2026-07-08', true));
    }

    public function test_bs_to_ad_accepts_devanagari_digits(): void
    {
        $this->assertSame('2026-07-08', $this->date->bsToAd('२०८३-०३-२४'));
    }

    public function test_bs_to_ad_accepts_month_name_format(): void
    {
        $this->assertSame('2026-07-08', $this->date->bsToAd('24 Ashadh 2083'));
        $this->assertSame('2026-07-08', $this->date->bsToAd('24 असार 2083'));
    }

    public function test_month_name_english_and_nepali(): void
    {
        $this->assertSame('Ashadh', $this->date->getMonth(3));
        $this->assertSame('असार', $this->date->getMonth(3, true));
    }

    public function test_day_number_formatting(): void
    {
        $this->assertSame('15', $this->date->getDay(15));
        $this->assertSame('१५', $this->date->getDay(15, true));
    }

    public function test_day_name_from_ad_date(): void
    {
        // 2026-07-08 is a Wednesday
        $this->assertSame('Wednesday', $this->date->getDayName('2026-07-08'));
        $this->assertSame('बुधबार', $this->date->getDayName('2026-07-08', true));
    }

    public function test_day_name_from_bs_date_after_converting_to_ad(): void
    {
        $ad = $this->date->bsToAd('2083-03-24');
        $this->assertSame('Wednesday', $this->date->getDayName($ad));
    }

    public function test_format_full_date_string(): void
    {
        $this->assertSame('24 Ashadh 2083', $this->date->format('2083-03-24'));
        $this->assertSame('२४ असार २०८३', $this->date->format('2083-03-24', true));
    }

    public function test_bs_to_nepali_text(): void
    {
        $this->assertSame('२४ असार २०८३', $this->date->bsToNepaliText('2083-03-24'));
    }

    public function test_supported_year_range(): void
    {
        $this->assertSame([2000, 2100], $this->date->getSupportedYearRange());
    }

    public function test_invalid_bs_date_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->date->bsToAd('not-a-date');
    }

    public function test_out_of_range_year_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->date->bsToAd('1999-01-01');
    }

    public function test_invalid_month_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->date->getMonth(13);
    }
}
