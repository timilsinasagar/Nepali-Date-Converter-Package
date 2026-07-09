<?php
// src/ValueObjects/NepaliDateResult.php

namespace Sagartimilsina\NepaliDate\ValueObjects;

use Carbon\Carbon;
use JsonSerializable;

/**
 * Immutable result of a BS conversion: every piece of information you'd
 * normally have to derive yourself, bundled together -- including the
 * equivalent Carbon (AD) instance so you can keep using Carbon's API
 * for anything this package doesn't cover directly.
 */
final class NepaliDateResult implements JsonSerializable
{
    public function __construct(
        public readonly int $year,
        public readonly int $month,
        public readonly string $monthNameEn,
        public readonly string $monthNameNp,
        public readonly int $day,
        public readonly string $dayEn,
        public readonly string $dayNp,
        public readonly string $weekdayEn,
        public readonly string $weekdayNp,
        public readonly string $formattedEn,
        public readonly string $formattedNp,
        public readonly Carbon $carbon,
    ) {
    }

    /**
     * "2083-03-24" style numeric BS string, English digits.
     */
    public function toDateString(): string
    {
        return sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
    }

    /**
     * "२०८३-०३-२४" style numeric BS string, Devanagari digits.
     */
    public function toDevanagariDateString(): string
    {
        return \Sagartimilsina\NepaliDate\Converter\InputParser::toDevanagariDigits($this->toDateString());
    }

    public function toArray(): array
    {
        return [
            'year' => $this->year,
            'month' => $this->month,
            'month_name_en' => $this->monthNameEn,
            'month_name_np' => $this->monthNameNp,
            'day' => $this->day,
            'day_en' => $this->dayEn,
            'day_np' => $this->dayNp,
            'weekday_en' => $this->weekdayEn,
            'weekday_np' => $this->weekdayNp,
            'formatted_en' => $this->formattedEn,
            'formatted_np' => $this->formattedNp,
            'bs_date' => $this->toDateString(),
            'bs_date_np' => $this->toDevanagariDateString(),
            'ad_date' => $this->carbon->format('Y-m-d'),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->formattedEn;
    }
}
