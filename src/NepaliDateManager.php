<?php
// src/NepaliDateManager.php

namespace Sagartimilsina\NepaliDate;

use Carbon\Carbon;
use DateTimeInterface;
use InvalidArgumentException;
use Sagartimilsina\NepaliDate\Converter\BsAdConverter;
use Sagartimilsina\NepaliDate\Converter\CalendarData;
use Sagartimilsina\NepaliDate\Converter\InputParser;
use Sagartimilsina\NepaliDate\Converter\NameData;
use Sagartimilsina\NepaliDate\ValueObjects\NepaliDateResult;

class NepaliDateManager
{
    public function __construct(private BsAdConverter $converter)
    {
    }

    // ------------------------------------------------------------------
    // Primary API (v2.0.0) -- rich results, Carbon-backed
    // ------------------------------------------------------------------

    /**
     * Convert an AD date to a full BS result.
     *
     * Accepts a Carbon instance, any other DateTimeInterface, or a
     * "Y-m-d" style string.
     */
    public function adToBs(string|DateTimeInterface $adDate): NepaliDateResult
    {
        $carbon = $this->toCarbonInstance($adDate);

        $bs = $this->converter->adToBs($carbon->format('Y-m-d'));

        return $this->buildResult($bs['year'], $bs['month'], $bs['day'], $carbon);
    }

    /**
     * Convert a BS date (numeric, Devanagari, or "15 Ashadh 2081" style
     * text) to its equivalent Carbon (AD) instance.
     */
    public function bsToAd(string $bsDate): Carbon
    {
        $parsed = InputParser::parseBsDate($bsDate);
        $adString = $this->converter->bsToAd($parsed['year'], $parsed['month'], $parsed['day']);

        return Carbon::parse($adString)->startOfDay();
    }

    /**
     * Alias of bsToAd() -- convert a BS date straight to a Carbon instance.
     * Reads more naturally at call sites like `NepaliDate::toCarbon($bs)->diffForHumans()`.
     */
    public function toCarbon(string $bsDate): Carbon
    {
        return $this->bsToAd($bsDate);
    }

    /**
     * Today's date (midnight), converted to a full BS result.
     */
    public function todayBS(): NepaliDateResult
    {
        return $this->adToBs(Carbon::today());
    }

    /**
     * Today's date (midnight) as a Carbon (AD) instance.
     */
    public function todayAD(): Carbon
    {
        return Carbon::today();
    }

    /**
     * The current moment (date + time), converted to a full BS result.
     * The BS year/month/day is still derived from the calendar date only --
     * the time-of-day lives on the attached Carbon instance.
     */
    public function nowBS(): NepaliDateResult
    {
        return $this->adToBs(Carbon::now());
    }

    /**
     * The current moment (date + time) as a Carbon (AD) instance.
     */
    public function nowAD(): Carbon
    {
        return Carbon::now();
    }

    // ------------------------------------------------------------------
    // Legacy string-based helpers (kept from v1.x)
    //
    // adToBs()/bsToAd() changed return type in v2.0.0 (breaking change --
    // see CHANGELOG.md). These *_String variants preserve the old v1.x
    // string-in/string-out behavior exactly, for anyone upgrading who
    // doesn't need the richer result object yet.
    // ------------------------------------------------------------------

    public function adToBsString(string $adDate, bool $devanagari = false): string
    {
        $result = $this->adToBs($adDate);

        return $devanagari ? $result->toDevanagariDateString() : $result->toDateString();
    }

    public function bsToAdString(string $bsDate): string
    {
        return $this->bsToAd($bsDate)->format('Y-m-d');
    }

    public function bsToNepaliText(string $bsDate): string
    {
        return $this->adToBs($this->bsToAd($bsDate))->formattedNp;
    }

    public function format(string $bsDate, bool $devanagari = false): string
    {
        $result = $this->adToBs($this->bsToAd($bsDate));

        return $devanagari ? $result->formattedNp : $result->formattedEn;
    }

    public function getMonth(int $month, bool $devanagari = false): string
    {
        return NameData::monthName($month, $devanagari);
    }

    public function getDay(int $day, bool $devanagari = false): string
    {
        if ($day < 1 || $day > 32) {
            throw new InvalidArgumentException("Invalid day number: {$day}");
        }

        return $devanagari ? InputParser::toDevanagariDigits((string) $day) : (string) $day;
    }

    /**
     * Weekday name for a given AD date. Kept for v1.x compatibility --
     * prefer adToBs($date)->weekdayEn / ->weekdayNp going forward.
     */
    public function getDayName(string $adDate, bool $devanagari = false): string
    {
        $result = $this->adToBs($adDate);

        return $devanagari ? $result->weekdayNp : $result->weekdayEn;
    }

    public function monthName(int $month, bool $devanagari = false): string
    {
        return $this->getMonth($month, $devanagari);
    }

    public function dayName(string $adDate, bool $devanagari = false): string
    {
        return $this->getDayName($adDate, $devanagari);
    }

    public function today(bool $devanagari = false): string
    {
        $result = $this->todayBS();

        return $devanagari ? $result->toDevanagariDateString() : $result->toDateString();
    }

    public function getSupportedYearRange(): array
    {
        return CalendarData::getSupportedYearRange();
    }

    // ------------------------------------------------------------------
    // Internal helpers
    // ------------------------------------------------------------------

    private function toCarbonInstance(string|DateTimeInterface $adDate): Carbon
    {
        if ($adDate instanceof Carbon) {
            return $adDate->copy()->startOfDay();
        }

        if ($adDate instanceof DateTimeInterface) {
            return Carbon::parse($adDate->format('Y-m-d'))->startOfDay();
        }

        return Carbon::parse(InputParser::toEnglishDigits(trim($adDate)))->startOfDay();
    }

    private function buildResult(int $year, int $month, int $day, Carbon $carbon): NepaliDateResult
    {
        // The weekday is always derived from the actual converted date --
        // never hardcoded against a specific BS/AD value. English comes
        // straight from Carbon's own weekday calculation; Nepali is a
        // translation lookup keyed by that same derived weekday index
        // (0 = Sunday .. 6 = Saturday), not a per-date table.
        $weekdayIndex = (int) $carbon->format('w');

        $monthNameEn = NameData::monthName($month, false);
        $monthNameNp = NameData::monthName($month, true);

        $dayEn = (string) $day;
        $dayNp = InputParser::toDevanagariDigits($dayEn);
        $yearNp = InputParser::toDevanagariDigits((string) $year);

        return new NepaliDateResult(
            year: $year,
            month: $month,
            monthNameEn: $monthNameEn,
            monthNameNp: $monthNameNp,
            day: $day,
            dayEn: $dayEn,
            dayNp: $dayNp,
            weekdayEn: $carbon->format('l'),
            weekdayNp: NameData::dayName($weekdayIndex, true),
            formattedEn: "{$dayEn} {$monthNameEn} {$year}",
            formattedNp: "{$dayNp} {$monthNameNp} {$yearNp}",
            carbon: $carbon,
        );
    }
}