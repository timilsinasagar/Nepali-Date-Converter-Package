<?php
// config/nepali-date.php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Output Language
    |--------------------------------------------------------------------------
    |
    | Controls whether helper methods that accept an optional $devanagari
    | flag fall back to Devanagari (Nepali) digits/names by default when
    | you resolve the manager manually. The facade methods still let you
    | override this per call.
    |
    */
    'default_devanagari' => false,

    /*
    |--------------------------------------------------------------------------
    | Supported BS Year Range
    |--------------------------------------------------------------------------
    |
    | Informational only — the actual range is derived from the data in
    | Sagartimilsina\NepaliDate\Converter\CalendarData. Kept here so it is
    | easy to see at a glance without digging into the source.
    |
    */
    'supported_bs_years' => [2000, 2100],

    /*
    |--------------------------------------------------------------------------
    | AD Reference Date
    |--------------------------------------------------------------------------
    |
    | The Gregorian date that BS year 2000, month 1, day 1 corresponds to.
    | Used internally as the anchor point for all conversions.
    |
    */
    'ad_reference_date' => '1943-04-14',

];
