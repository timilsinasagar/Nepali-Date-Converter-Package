<?php
// src/Converter/CalendarData.php

namespace Sagartimilsina\NepaliDate\Converter;

class CalendarData
{
    // Reference point: BS year start date in AD
    public const BS_YEAR_START = 1970; // earliest year in table
    public const AD_REFERENCE_DATE = '1913-04-13'; // 1970-01-01 BS

    // Each entry: [year => [days in each of 12 months]]
    // SOURCE THIS FROM A VERIFIED DATASET — placeholder shown for structure only
    public static array $calendarData = [
        1970 => [30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
        1971 => [31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
        // ... continue for full range (1970–2090 or as far as you need)
    ];

    public static function getMonthDays(int $bsYear): array
    {
        if (!isset(self::$calendarData[$bsYear])) {
            throw new \OutOfRangeException("No calendar data for BS year {$bsYear}");
        }
        return self::$calendarData[$bsYear];
    }

    public static function getSupportedYearRange(): array
    {
        $years = array_keys(self::$calendarData);
        return [min($years), max($years)];
    }
}