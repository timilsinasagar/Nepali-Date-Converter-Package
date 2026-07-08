<?php
// src/NepaliDate.php

namespace Sagartimilsina\NepaliDate;

use Sagartimilsina\NepaliDate\Converter\BsAdConverter;

/**
 * Plain-PHP entry point (no Laravel required).
 *
 * Usage:
 *   $date = new NepaliDate();
 *   echo $date->bsToAd('2083-03-24');
 */
class NepaliDate
{
    private NepaliDateManager $manager;

    public function __construct()
    {
        $this->manager = new NepaliDateManager(new BsAdConverter());
    }

    public function adToBs(string $adDate, bool $devanagari = false): string
    {
        return $this->manager->adToBs($adDate, $devanagari);
    }

    public function bsToAd(string $bsDate): string
    {
        return $this->manager->bsToAd($bsDate);
    }

    public function bsToNepaliText(string $bsDate): string
    {
        return $this->manager->bsToNepaliText($bsDate);
    }

    public function format(string $bsDate, bool $devanagari = false): string
    {
        return $this->manager->format($bsDate, $devanagari);
    }

    public function getMonth(int $month, bool $devanagari = false): string
    {
        return $this->manager->getMonth($month, $devanagari);
    }

    public function getDay(int $day, bool $devanagari = false): string
    {
        return $this->manager->getDay($day, $devanagari);
    }

    public function getDayName(string $adDate, bool $devanagari = false): string
    {
        return $this->manager->getDayName($adDate, $devanagari);
    }

    public function monthName(int $month, bool $devanagari = false): string
    {
        return $this->manager->monthName($month, $devanagari);
    }

    public function dayName(string $adDate, bool $devanagari = false): string
    {
        return $this->manager->dayName($adDate, $devanagari);
    }

    public function today(bool $devanagari = false): string
    {
        return $this->manager->today($devanagari);
    }

    public function getSupportedYearRange(): array
    {
        return $this->manager->getSupportedYearRange();
    }
}