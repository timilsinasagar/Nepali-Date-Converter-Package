<?php
// src/NepaliDateManager.php

namespace Sagartimilsina\NepaliDate;

use Sagartimilsina\NepaliDate\Converter\BsAdConverter;
use Sagartimilsina\NepaliDate\Converter\InputParser;

class NepaliDateManager
{
    public function __construct(private BsAdConverter $converter) {}

    public function adToBs(string $adDate, bool $devanagariOutput = false): string
    {
        $bs = $this->converter->adToBs($adDate);
        $formatted = sprintf('%04d-%02d-%02d', $bs['year'], $bs['month'], $bs['day']);
        return $devanagariOutput ? InputParser::toDevanagariDigits($formatted) : $formatted;
    }

    public function bsToAd(string $bsDate): string
    {
        $parsed = InputParser::parseBsDate($bsDate);
        return $this->converter->bsToAd($parsed['year'], $parsed['month'], $parsed['day']);
    }

    public function bsToNepaliText(string $bsDate): string
    {
        $parsed = InputParser::parseBsDate($bsDate);
        $monthName = InputParser::nepaliMonthName($parsed['month']);
        $day = InputParser::toDevanagariDigits((string) $parsed['day']);
        $year = InputParser::toDevanagariDigits((string) $parsed['year']);
        return "{$day} {$monthName} {$year}";
    }
}