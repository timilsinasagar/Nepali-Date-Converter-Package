<?php
// src/Converter/BsAdConverter.php

namespace Sagartimilsina\NepaliDate\Converter;

use DateTime;
use InvalidArgumentException;

class BsAdConverter
{
    public function adToBs(string $adDate): array
    {
        $date = new DateTime($adDate);
        $refDate = new DateTime(CalendarData::AD_REFERENCE_DATE);

        if ($date < $refDate) {
            throw new InvalidArgumentException('Date is before supported range.');
        }

        $totalDays = (int) $refDate->diff($date)->days;

        $bsYear = CalendarData::BS_YEAR_START;
        $bsMonth = 1;
        $bsDay = 1;

        while (true) {
            $monthDays = CalendarData::getMonthDays($bsYear);

            foreach ($monthDays as $monthIndex => $daysInMonth) {
                if ($totalDays < $daysInMonth) {
                    $bsMonth = $monthIndex + 1;
                    $bsDay = $totalDays + 1;
                    return [
                        'year' => $bsYear,
                        'month' => $bsMonth,
                        'day' => $bsDay,
                    ];
                }
                $totalDays -= $daysInMonth;
            }
            $bsYear++;
        }
    }

    public function bsToAd(int $bsYear, int $bsMonth, int $bsDay): string
    {
        [$minYear, $maxYear] = CalendarData::getSupportedYearRange();
        if ($bsYear < $minYear || $bsYear > $maxYear) {
            throw new InvalidArgumentException("BS year {$bsYear} outside supported range.");
        }

        $totalDays = 0;
        for ($year = $minYear; $year < $bsYear; $year++) {
            $totalDays += array_sum(CalendarData::getMonthDays($year));
        }

        $monthDays = CalendarData::getMonthDays($bsYear);
        for ($m = 0; $m < $bsMonth - 1; $m++) {
            $totalDays += $monthDays[$m];
        }
        $totalDays += $bsDay - 1;

        $refDate = new DateTime(CalendarData::AD_REFERENCE_DATE);
        $refDate->modify("+{$totalDays} days");

        return $refDate->format('Y-m-d');
    }
}