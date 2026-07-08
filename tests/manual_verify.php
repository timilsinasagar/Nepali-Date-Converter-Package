<?php
// Manual verification script (no Composer/PHPUnit required).
// Run: php tests/manual_verify.php

require __DIR__ . '/../src/Converter/CalendarData.php';
require __DIR__ . '/../src/Converter/NameData.php';
require __DIR__ . '/../src/Converter/InputParser.php';
require __DIR__ . '/../src/Converter/BsAdConverter.php';
require __DIR__ . '/../src/NepaliDateManager.php';
require __DIR__ . '/../src/NepaliDate.php';

use Sagartimilsina\NepaliDate\NepaliDate;

$failures = 0;
$passed = 0;

function check(string $label, $actual, $expected): void
{
    global $failures, $passed;
    if ($actual === $expected) {
        $passed++;
        echo "PASS: {$label}\n";
    } else {
        $failures++;
        echo "FAIL: {$label} -- expected [" . var_export($expected, true) . "] got [" . var_export($actual, true) . "]\n";
    }
}

function checkThrows(string $label, callable $fn): void
{
    global $failures, $passed;
    try {
        $fn();
        $failures++;
        echo "FAIL: {$label} -- expected exception, none thrown\n";
    } catch (\InvalidArgumentException|\OutOfRangeException $e) {
        $passed++;
        echo "PASS: {$label} ({$e->getMessage()})\n";
    }
}

$date = new NepaliDate();

check('AD to BS', $date->adToBs('2026-07-08'), '2083-03-24');
check('BS to AD', $date->bsToAd('2083-03-24'), '2026-07-08');

$ad = '2025-01-15';
check('Round trip', $date->bsToAd($date->adToBs($ad)), $ad);

check('AD to BS devanagari', $date->adToBs('2026-07-08', true), '२०८३-०३-२४');
check('BS to AD from devanagari input', $date->bsToAd('२०८३-०३-२४'), '2026-07-08');
check('BS to AD from English month-name text', $date->bsToAd('24 Ashadh 2083'), '2026-07-08');
check('BS to AD from Nepali month-name text', $date->bsToAd('24 असार 2083'), '2026-07-08');

check('Month name EN', $date->getMonth(3), 'Ashadh');
check('Month name NP', $date->getMonth(3, true), 'असार');

check('Day number EN', $date->getDay(15), '15');
check('Day number NP', $date->getDay(15, true), '१५');

check('Day name from AD (Wed)', $date->getDayName('2026-07-08'), 'Wednesday');
check('Day name from AD NP', $date->getDayName('2026-07-08', true), 'बुधबार');
check('Day name from BS date (convert first)', $date->getDayName($date->bsToAd('2083-03-24')), 'Wednesday');

check('Format EN', $date->format('2083-03-24'), '24 Ashadh 2083');
check('Format NP', $date->format('2083-03-24', true), '२४ असार २०८३');
check('bsToNepaliText', $date->bsToNepaliText('2083-03-24'), '२४ असार २०८३');

check('Supported year range', $date->getSupportedYearRange(), [2000, 2100]);

checkThrows('Invalid date string throws', fn() => $date->bsToAd('not-a-date'));
checkThrows('Out of range year throws', fn() => $date->bsToAd('1999-01-01'));
checkThrows('Invalid month throws', fn() => $date->getMonth(13));

// A handful of extra round-trip spot checks across the supported range.
foreach (['2000-01-01', '2050-06-15', '2081-01-01', '2100-12-30'] as $bsSample) {
    $ad2 = $date->bsToAd($bsSample);
    $back = $date->adToBs($ad2);
    check("Round trip BS {$bsSample}", $back, $bsSample);
}

echo "\n{$passed} passed, {$failures} failed.\n";
exit($failures > 0 ? 1 : 0);