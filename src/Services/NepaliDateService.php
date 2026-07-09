<?php
// src/Services/NepaliDateService.php

namespace Sagartimilsina\NepaliDate\Services;

use Sagartimilsina\NepaliDate\NepaliDateManager;

/**
 * Thin service layer over NepaliDateManager for the bundled demo.
 *
 * The controller should only ever call into this class -- it never
 * touches NepaliDateManager, InputParser, or Carbon directly. If you're
 * building your own app on top of this package, feel free to use this
 * class as a template for your own service layer rather than calling
 * the facade straight from controllers.
 */
class NepaliDateService
{
    public function __construct(private NepaliDateManager $manager)
    {
    }

    /**
     * Convert per the validated request's declared direction, returning a
     * flat array the demo view can render directly.
     *
     * @throws \InvalidArgumentException if the date_value doesn't parse
     *         for the requested direction.
     */
    public function convert(string $direction, string $dateValue): array
    {
        return match ($direction) {
            'ad_to_bs' => $this->convertAdToBs($dateValue),
            'bs_to_ad' => $this->convertBsToAd($dateValue),
            default => throw new \InvalidArgumentException("Unsupported direction: {$direction}"),
        };
    }

    private function convertAdToBs(string $adDate): array
    {
        $result = $this->manager->adToBs($adDate);

        return [
            'direction' => 'ad_to_bs',
            'input' => $adDate,
            'bs_date' => $result->toDateString(),
            'bs_date_np' => $result->toDevanagariDateString(),
            'month_name_en' => $result->monthNameEn,
            'month_name_np' => $result->monthNameNp,
            'weekday_en' => $result->weekdayEn,
            'weekday_np' => $result->weekdayNp,
            'formatted_en' => $result->formattedEn,
            'formatted_np' => $result->formattedNp,
            'ad_date' => $result->carbon->format('Y-m-d'),
        ];
    }

    private function convertBsToAd(string $bsDate): array
    {
        $carbon = $this->manager->bsToAd($bsDate);
        $result = $this->manager->adToBs($carbon);

        return [
            'direction' => 'bs_to_ad',
            'input' => $bsDate,
            'ad_date' => $carbon->format('Y-m-d'),
            'weekday_en' => $result->weekdayEn,
            'weekday_np' => $result->weekdayNp,
            'formatted_en' => $result->formattedEn,
            'formatted_np' => $result->formattedNp,
            'bs_date' => $result->toDateString(),
            'bs_date_np' => $result->toDevanagariDateString(),
        ];
    }

    /**
     * Convenience for the demo's "today" panel.
     */
    public function today(): array
    {
        $result = $this->manager->todayBS();

        return [
            'ad_date' => $this->manager->todayAD()->format('Y-m-d'),
            'bs_date' => $result->toDateString(),
            'bs_date_np' => $result->toDevanagariDateString(),
            'formatted_en' => $result->formattedEn,
            'formatted_np' => $result->formattedNp,
            'weekday_en' => $result->weekdayEn,
            'weekday_np' => $result->weekdayNp,
        ];
    }
}
