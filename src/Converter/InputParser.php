<?php
// src/Converter/InputParser.php

namespace Sagartimilsina\NepaliDate\Converter;

use InvalidArgumentException;

class InputParser
{
    private const DEVANAGARI_DIGITS = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
    private const ENGLISH_DIGITS = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    private const NEPALI_MONTHS = [
        'बैशाख' => 1,
        'जेठ' => 2,
        'असार' => 3,
        'साउन' => 4,
        'भदौ' => 5,
        'असोज' => 6,
        'कार्तिक' => 7,
        'मंसिर' => 8,
        'पुष' => 9,
        'माघ' => 10,
        'फागुन' => 11,
        'चैत' => 12,
    ];

    public static function toEnglishDigits(string $input): string
    {
        return str_replace(self::DEVANAGARI_DIGITS, self::ENGLISH_DIGITS, $input);
    }

    public static function toDevanagariDigits(string $input): string
    {
        return str_replace(self::ENGLISH_DIGITS, self::DEVANAGARI_DIGITS, $input);
    }

    /**
     * Parses "2081-03-15", "२०८१-०३-१५", or "15 असार 2081" into [year, month, day]
     */
    public static function parseBsDate(string $input): array
    {
        $normalized = self::toEnglishDigits(trim($input));

        // Numeric format: YYYY-MM-DD or YYYY/MM/DD
        if (preg_match('/^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})$/', $normalized, $m)) {
            return ['year' => (int) $m[1], 'month' => (int) $m[2], 'day' => (int) $m[3]];
        }

        // Nepali month-name format: "15 असार 2081" or "असार 15, 2081"
        foreach (self::NEPALI_MONTHS as $name => $index) {
            if (str_contains($input, $name)) {
                preg_match('/(\d+)/', self::toEnglishDigits($input), $numbers, PREG_OFFSET_CAPTURE);
                preg_match_all('/\d+/', $normalized, $allNumbers);
                if (count($allNumbers[0]) >= 2) {
                    return [
                        'year' => (int) end($allNumbers[0]),
                        'month' => $index,
                        'day' => (int) $allNumbers[0][0],
                    ];
                }
            }
        }

        throw new InvalidArgumentException("Unable to parse BS date: {$input}");
    }

    public static function nepaliMonthName(int $monthIndex): string
    {
        $names = array_flip(self::NEPALI_MONTHS);
        return $names[$monthIndex] ?? throw new InvalidArgumentException('Invalid month index');
    }
}
