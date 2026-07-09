<?php
// config/nepali-date.php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Output Language
    |--------------------------------------------------------------------------
    |
    | Controls whether legacy string-based helper methods (getMonth(),
    | getDay(), etc.) fall back to Devanagari digits/names by default.
    | Every call site can still override this per call via the
    | $devanagari argument.
    |
    */
    'default_devanagari' => false,

    /*
    |--------------------------------------------------------------------------
    | Supported BS Year Range
    |--------------------------------------------------------------------------
    |
    | Informational only -- the actual range is derived from the data in
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

    /*
    |--------------------------------------------------------------------------
    | Bundled Demo Route
    |--------------------------------------------------------------------------
    |
    | Disabled by default -- the package never adds a route to your app
    | without an explicit opt-in. Set to true (or NEPALI_DATE_DEMO_ROUTE=true
    | in .env) to mount the Bootstrap 5 conversion demo at demo_route_path.
    | Meant for local exploration only; don't leave this on in production.
    |
    */
    'demo_route_enabled' => env('NEPALI_DATE_DEMO_ROUTE', false),

    'demo_route_path' => env('NEPALI_DATE_DEMO_PATH', '/nepali-date-demo'),

];