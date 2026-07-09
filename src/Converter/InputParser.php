<?php
// src/Converter/InputParser.php

namespace Sagartimilsina\NepaliDate\Converter;

use InvalidArgumentException;

class InputParser
{
    private const DEVANAGARI_DIGITS = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
    private const ENGLISH_DIGITS = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    public static function toEnglishDigits(string $input): string
    {
        return str_replace(self::DEVANAGARI_DIGITS, self::ENGLISH_DIGITS, $input);
    }

    public static function toDevanagariDigits(string $input): string
    {
        return str_replace(self::ENGLISH_DIGITS, self::DEVANAGARI_DIGITS, $input);
    }

    /**
     * Parses "2081-03-15", "२०८१-०३-१५", or "15 असार 2081" (or "Ashadh 15, 2081")
     * into ['year' => int, 'month' => int, 'day' => int]
     */
    public static function parseBsDate(string $input): array
    {
        $trimmed = trim($input);
        $normalized = self::toEnglishDigits($trimmed);

        // Numeric format: YYYY-MM-DD or YYYY/MM/DD
        if (preg_match('/^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})$/', $normalized, $m)) {
            return self::assertValidParts((int) $m[1], (int) $m[2], (int) $m[3], $input);
        }

        // Month-name format (English or Nepali): "15 असार 2081", "Ashadh 15, 2081", "15 Ashadh 2081"
        foreach (self::monthNameCandidates() as $name => $index) {
            if (stripos($trimmed, $name) !== false) {
                preg_match_all('/\d+/', $normalized, $allNumbers);

                if (count($allNumbers[0]) >= 2) {
                    $day = (int) $allNumbers[0][0];
                    $year = (int) end($allNumbers[0]);

                    return self::assertValidParts($year, $index, $day, $input);
                }
            }
        }

        throw new InvalidArgumentException("Unable to parse BS date: {$input}");
    }

    /**
     * Combines Nepali and English month names into a single lookup table,
     * ordered so multi-syllable / longer names are checked before short ones.
     *
     * @return array<string, int>
     */
    private static function monthNameCandidates(): array
    {
        $candidates = [];

        foreach (NameData::$monthNamesNp as $index => $name) {
            $candidates[$name] = $index;
        }

        foreach (NameData::$monthNamesEn as $index => $name) {
            $candidates[$name] = $index;
        }

        return $candidates;
    }

    private static function assertValidParts(int $year, int $month, int $day, string $original): array
    {
        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException("Invalid BS month in date: {$original}");
        }

        if ($day < 1 || $day > 32) {
            throw new InvalidArgumentException("Invalid BS day in date: {$original}");
        }

        return ['year' => $year, 'month' => $month, 'day' => $day];
    }

    public static function nepaliMonthName(int $monthIndex): string
    {
        return NameData::monthName($monthIndex, true);
    }
}