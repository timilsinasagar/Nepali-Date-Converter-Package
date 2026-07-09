<?php
// src/NepaliDate.php

namespace Sagartimilsina\NepaliDate;

use Carbon\Carbon;
use DateTimeInterface;
use Sagartimilsina\NepaliDate\Converter\BsAdConverter;
use Sagartimilsina\NepaliDate\ValueObjects\NepaliDateResult;

/**
 * Plain-PHP entry point (no Laravel service container required).
 *
 * Usage:
 *   $date = new NepaliDate();
 *   $result = $date->adToBs('2026-07-08');
 *   echo $result->formattedEn; // "24 Ashadh 2083"
 */
class NepaliDate
{
    private NepaliDateManager $manager;

    public function __construct()
    {
        $this->manager = new NepaliDateManager(new BsAdConverter());
    }

    public function adToBs(string|DateTimeInterface $adDate): NepaliDateResult
    {
        return $this->manager->adToBs($adDate);
    }

    public function bsToAd(string $bsDate): Carbon
    {
        return $this->manager->bsToAd($bsDate);
    }

    public function toCarbon(string $bsDate): Carbon
    {
        return $this->manager->toCarbon($bsDate);
    }

    public function todayBS(): NepaliDateResult
    {
        return $this->manager->todayBS();
    }

    public function todayAD(): Carbon
    {
        return $this->manager->todayAD();
    }

    public function nowBS(): NepaliDateResult
    {
        return $this->manager->nowBS();
    }

    public function nowAD(): Carbon
    {
        return $this->manager->nowAD();
    }

    // -- Legacy string-based helpers (v1.x compatibility) --

    public function adToBsString(string $adDate, bool $devanagari = false): string
    {
        return $this->manager->adToBsString($adDate, $devanagari);
    }

    public function bsToAdString(string $bsDate): string
    {
        return $this->manager->bsToAdString($bsDate);
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