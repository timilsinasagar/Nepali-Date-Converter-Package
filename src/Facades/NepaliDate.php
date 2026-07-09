<?php
// src/Facades/NepaliDate.php

namespace Sagartimilsina\NepaliDate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Sagartimilsina\NepaliDate\ValueObjects\NepaliDateResult adToBs(string|\DateTimeInterface $adDate)
 * @method static \Carbon\Carbon bsToAd(string $bsDate)
 * @method static \Carbon\Carbon toCarbon(string $bsDate)
 * @method static \Sagartimilsina\NepaliDate\ValueObjects\NepaliDateResult todayBS()
 * @method static \Carbon\Carbon todayAD()
 * @method static \Sagartimilsina\NepaliDate\ValueObjects\NepaliDateResult nowBS()
 * @method static \Carbon\Carbon nowAD()
 * @method static array getSupportedYearRange()
 *
 * Legacy v1.x string-based helpers (still supported):
 * @method static string adToBsString(string $adDate, bool $devanagari = false)
 * @method static string bsToAdString(string $bsDate)
 * @method static string bsToNepaliText(string $bsDate)
 * @method static string format(string $bsDate, bool $devanagari = false)
 * @method static string getMonth(int $month, bool $devanagari = false)
 * @method static string getDay(int $day, bool $devanagari = false)
 * @method static string getDayName(string $adDate, bool $devanagari = false)
 * @method static string monthName(int $month, bool $devanagari = false)
 * @method static string dayName(string $adDate, bool $devanagari = false)
 * @method static string today(bool $devanagari = false)
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