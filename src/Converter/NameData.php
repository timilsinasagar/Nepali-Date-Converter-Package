<?php
// src/Converter/NameData.php

namespace Sagartimilsina\NepaliDate\Converter;

use InvalidArgumentException;

/**
 * Static lookup tables for BS month names and weekday names,
 * in both English (transliterated) and Nepali (Devanagari).
 */
class NameData
{
    /** @var array<int, string> Index 1-12 => English month name */
    public static array $monthNamesEn = [
        1 => 'Baishakh',
        2 => 'Jestha',
        3 => 'Ashadh',
        4 => 'Shrawan',
        5 => 'Bhadra',
        6 => 'Ashwin',
        7 => 'Kartik',
        8 => 'Mangsir',
        9 => 'Poush',
        10 => 'Magh',
        11 => 'Falgun',
        12 => 'Chaitra',
    ];

    /** @var array<int, string> Index 1-12 => Nepali (Devanagari) month name */
    public static array $monthNamesNp = [
        1 => 'बैशाख',
        2 => 'जेठ',
        3 => 'असार',
        4 => 'साउन',
        5 => 'भदौ',
        6 => 'असोज',
        7 => 'कार्तिक',
        8 => 'मंसिर',
        9 => 'पुष',
        10 => 'माघ',
        11 => 'फागुन',
        12 => 'चैत',
    ];

    /** @var array<int, string> Index 0 (Sunday) - 6 (Saturday) => English day name */
    public static array $dayNamesEn = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    /** @var array<int, string> Index 0 (Sunday) - 6 (Saturday) => Nepali (Devanagari) day name */
    public static array $dayNamesNp = [
        0 => 'आइतबार',
        1 => 'सोमबार',
        2 => 'मंगलबार',
        3 => 'बुधबार',
        4 => 'बिहीबार',
        5 => 'शुक्रबार',
        6 => 'शनिबार',
    ];

    public static function monthName(int $month, bool $devanagari = false): string
    {
        $table = $devanagari ? self::$monthNamesNp : self::$monthNamesEn;

        return $table[$month] ?? throw new InvalidArgumentException("Invalid month number: {$month}");
    }

    /**
     * @param int $weekday 0 (Sunday) through 6 (Saturday), matching PHP's DateTime::format('w')
     */
    public static function dayName(int $weekday, bool $devanagari = false): string
    {
        $table = $devanagari ? self::$dayNamesNp : self::$dayNamesEn;

        return $table[$weekday] ?? throw new InvalidArgumentException("Invalid weekday index: {$weekday}");
    }

    public static function monthIndexFromName(string $name): int
    {
        $flippedNp = array_flip(self::$monthNamesNp);
        if (isset($flippedNp[$name])) {
            return $flippedNp[$name];
        }

        foreach (self::$monthNamesEn as $index => $enName) {
            if (strcasecmp($enName, $name) === 0) {
                return $index;
            }
        }

        throw new InvalidArgumentException("Unknown month name: {$name}");
    }
}