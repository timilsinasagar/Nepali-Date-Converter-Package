<?php
// src/Converter/BsAdConverter.php

namespace Sagartimilsina\NepaliDate\Converter;

use DateTime;
use InvalidArgumentException;

class BsAdConverter
{
    /**
     * @return array{year: int, month: int, day: int}
     */
    public function adToBs(string $adDate): array
    {
        $date = new DateTime($adDate);
        $refDate = new DateTime(CalendarData::AD_REFERENCE_DATE);

        if ($date < $refDate) {
            throw new InvalidArgumentException('Date is before supported range.');
        }

        $totalDays = (int) $refDate->diff($date)->days;

        $bsYear = CalendarData::BS_YEAR_START;

        while (true) {
            $monthDays = CalendarData::getMonthDays($bsYear);

            foreach ($monthDays as $monthIndex => $daysInMonth) {
                if ($totalDays < $daysInMonth) {
                    return [
                        'year' => $bsYear,
                        'month' => $monthIndex + 1,
                        'day' => $totalDays + 1,
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
            throw new InvalidArgumentException("BS year {$bsYear} outside supported range ({$minYear}-{$maxYear}).");
        }

        if ($bsMonth < 1 || $bsMonth > 12) {
            throw new InvalidArgumentException("Invalid BS month: {$bsMonth}");
        }

        $monthDays = CalendarData::getMonthDays($bsYear);

        if ($bsDay < 1 || $bsDay > $monthDays[$bsMonth - 1]) {
            throw new InvalidArgumentException("Invalid BS day {$bsDay} for {$bsYear}-{$bsMonth}.");
        }

        $totalDays = 0;
        for ($year = $minYear; $year < $bsYear; $year++) {
            $totalDays += array_sum(CalendarData::getMonthDays($year));
        }

        for ($m = 0; $m < $bsMonth - 1; $m++) {
            $totalDays += $monthDays[$m];
        }
        $totalDays += $bsDay - 1;

        $refDate = new DateTime(CalendarData::AD_REFERENCE_DATE);
        $refDate->modify("+{$totalDays} days");

        return $refDate->format('Y-m-d');
    }
}