<?php
// src/NepaliDateManager.php

namespace Sagartimilsina\NepaliDate;

use DateTime;
use Sagartimilsina\NepaliDate\Converter\BsAdConverter;
use Sagartimilsina\NepaliDate\Converter\CalendarData;
use Sagartimilsina\NepaliDate\Converter\InputParser;
use Sagartimilsina\NepaliDate\Converter\NameData;

class NepaliDateManager
{
    public function __construct(private BsAdConverter $converter) {}

    /**
     * Convert an AD date (e.g. "2026-07-08") to a BS date string ("2083-03-24").
     */
    public function adToBs(string $adDate, bool $devanagari = false): string
    {
        $bs = $this->converter->adToBs($adDate);
        $formatted = sprintf('%04d-%02d-%02d', $bs['year'], $bs['month'], $bs['day']);

        return $devanagari ? InputParser::toDevanagariDigits($formatted) : $formatted;
    }

    /**
     * Convert a BS date (numeric, Devanagari, or "15 Ashadh 2081" style) to an AD date string.
     */
    public function bsToAd(string $bsDate): string
    {
        $parsed = InputParser::parseBsDate($bsDate);

        return $this->converter->bsToAd($parsed['year'], $parsed['month'], $parsed['day']);
    }

    /**
     * Render a BS date as Nepali text, e.g. "२४ असार २०८३".
     */
    public function bsToNepaliText(string $bsDate): string
    {
        return $this->format($bsDate, true);
    }

    /**
     * Full formatted BS date, e.g. "24 Ashadh 2083" (en) or "२४ असार २०८३" (np).
     */
    public function format(string $bsDate, bool $devanagari = false): string
    {
        $parsed = InputParser::parseBsDate($bsDate);
        $monthName = NameData::monthName($parsed['month'], $devanagari);

        $day = $devanagari ? InputParser::toDevanagariDigits((string) $parsed['day']) : (string) $parsed['day'];
        $year = $devanagari ? InputParser::toDevanagariDigits((string) $parsed['year']) : (string) $parsed['year'];

        return "{$day} {$monthName} {$year}";
    }

    /**
     * Get a BS month name by its 1-12 index.
     */
    public function getMonth(int $month, bool $devanagari = false): string
    {
        return NameData::monthName($month, $devanagari);
    }

    /**
     * Get a BS day number, formatted in English or Devanagari digits.
     */
    public function getDay(int $day, bool $devanagari = false): string
    {
        if ($day < 1 || $day > 32) {
            throw new \InvalidArgumentException("Invalid day number: {$day}");
        }

        return $devanagari ? InputParser::toDevanagariDigits((string) $day) : (string) $day;
    }

    /**
     * Get the weekday name for a given AD date (e.g. "2026-07-08"), in English or Devanagari.
     *
     * If you have a BS date, convert it first with bsToAd() and pass the result in.
     */
    public function getDayName(string $adDate, bool $devanagari = false): string
    {
        $weekday = (int) (new DateTime(InputParser::toEnglishDigits(trim($adDate))))->format('w');

        return NameData::dayName($weekday, $devanagari);
    }

    /**
     * Alias of getMonth(), matching the README's monthName() naming.
     */
    public function monthName(int $month, bool $devanagari = false): string
    {
        return $this->getMonth($month, $devanagari);
    }

    /**
     * Alias of getDayName(), matching the README's dayName() naming.
     */
    public function dayName(string $date, bool $devanagari = false): string
    {
        return $this->getDayName($date, $devanagari);
    }

    /**
     * Returns the [min, max] supported BS years.
     */
    public function getSupportedYearRange(): array
    {
        return CalendarData::getSupportedYearRange();
    }

    /**
     * Returns today's date converted to BS, as "YYYY-MM-DD".
     */
    public function today(bool $devanagari = false): string
    {
        return $this->adToBs(date('Y-m-d'), $devanagari);
    }
}
