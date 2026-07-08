<?php
// src/Facades/NepaliDate.php

namespace Sagartimilsina\NepaliDate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string adToBs(string $adDate, bool $devanagari = false)
 * @method static string bsToAd(string $bsDate)
 * @method static string bsToNepaliText(string $bsDate)
 * @method static string format(string $bsDate, bool $devanagari = false)
 * @method static string getMonth(int $month, bool $devanagari = false)
 * @method static string getDay(int $day, bool $devanagari = false)
 * @method static string getDayName(string $date, bool $devanagari = false)
 * @method static string monthName(int $month, bool $devanagari = false)
 * @method static string dayName(string $date, bool $devanagari = false)
 * @method static string today(bool $devanagari = false)
 * @method static array getSupportedYearRange()
 *
 * @see \Sagartimilsina\NepaliDate\NepaliDateManager
 */
class NepaliDate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Sagartimilsina\NepaliDate\NepaliDateManager::class;
    }
}
