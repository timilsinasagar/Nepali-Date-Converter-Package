<?php
// tests/manual_verify_v2.php
// Dev-only: loads the CarbonStub so this can run without a full Laravel install.
// Run: php tests/manual_verify_v2.php

require __DIR__ . '/../dev-stubs/CarbonStub.php'; // dev-only shim -- see file header
require __DIR__ . '/../src/Converter/CalendarData.php';
require __DIR__ . '/../src/Converter/NameData.php';
require __DIR__ . '/../src/Converter/InputParser.php';
require __DIR__ . '/../src/Converter/BsAdConverter.php';
require __DIR__ . '/../src/ValueObjects/NepaliDateResult.php';
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

$date = new NepaliDate();

// --- adToBs() returns a rich NepaliDateResult ---
$result = $date->adToBs('2026-07-08');
check('adToBs year', $result->year, 2083);
check('adToBs month', $result->month, 3);
check('adToBs day', $result->day, 24);
check('adToBs monthNameEn', $result->monthNameEn, 'Ashadh');
check('adToBs monthNameNp', $result->monthNameNp, 'असार');
check('adToBs weekdayEn (derived from Carbon, not hardcoded)', $result->weekdayEn, 'Wednesday');
check('adToBs weekdayNp', $result->weekdayNp, 'बुधबार');
check('adToBs formattedEn', $result->formattedEn, '24 Ashadh 2083');
check('adToBs formattedNp', $result->formattedNp, '२४ असार २०८३');
check('adToBs toDateString', $result->toDateString(), '2083-03-24');
check('adToBs toDevanagariDateString', $result->toDevanagariDateString(), '२०८३-०३-२४');
check('adToBs carbon AD date', $result->carbon->format('Y-m-d'), '2026-07-08');
check('adToBs __toString', (string) $result, '24 Ashadh 2083');

// --- bsToAd() returns a Carbon instance ---
$carbon = $date->bsToAd('2083-03-24');
check('bsToAd returns Carbon with right date', $carbon->format('Y-m-d'), '2026-07-08');
check('toCarbon() alias matches bsToAd()', $date->toCarbon('2083-03-24')->format('Y-m-d'), $carbon->format('Y-m-d'));

// --- round trip through the rich API ---
$roundTrip = $date->adToBs($date->bsToAd('2081-01-31'));
check('Round trip via rich API', $roundTrip->toDateString(), '2081-01-31');

// --- weekday correctness across known reference points (not hardcoded per-date) ---
$refs = [
    '2073-01-01' => 'Wednesday',
    '2080-01-01' => 'Friday',
    '2081-01-01' => 'Saturday',
    '2082-01-01' => 'Monday',
    '2083-01-01' => 'Tuesday',
];
foreach ($refs as $bs => $expectedWeekday) {
    $r = $date->adToBs($date->bsToAd($bs));
    check("Weekday for {$bs}", $r->weekdayEn, $expectedWeekday);
}

// --- accepts Carbon/DateTimeInterface input directly, not just strings ---
$fromCarbonInput = $date->adToBs($date->bsToAd('2083-03-24')); // Carbon in, result out
check('adToBs accepts Carbon input', $fromCarbonInput->toDateString(), '2083-03-24');

// --- toArray()/jsonSerialize() ---
$arr = $result->toArray();
check('toArray has bs_date', $arr['bs_date'], '2083-03-24');
check('toArray has ad_date', $arr['ad_date'], '2026-07-08');
check('jsonSerialize matches toArray', $result->jsonSerialize(), $arr);

// --- legacy v1.x string helpers still work ---
check('Legacy adToBsString', $date->adToBsString('2026-07-08'), '2083-03-24');
check('Legacy adToBsString devanagari', $date->adToBsString('2026-07-08', true), '२०८३-०३-२४');
check('Legacy bsToAdString', $date->bsToAdString('2083-03-24'), '2026-07-08');
check('Legacy format()', $date->format('2083-03-24'), '24 Ashadh 2083');
check('Legacy getMonth()', $date->getMonth(3), 'Ashadh');
check('Legacy getDay()', $date->getDay(15), '15');
check('Legacy getDayName()', $date->getDayName('2026-07-08'), 'Wednesday');
check('Legacy bsToNepaliText()', $date->bsToNepaliText('2083-03-24'), '२४ असार २०८३');
check('Legacy getSupportedYearRange()', $date->getSupportedYearRange(), [2000, 2100]);

// --- error handling still works ---
try {
    $date->bsToAd('not-a-date');
    $failures++;
    echo "FAIL: Invalid BS date should throw\n";
} catch (\InvalidArgumentException $e) {
    $passed++;
    echo "PASS: Invalid BS date throws ({$e->getMessage()})\n";
}

echo "\n{$passed} passed, {$failures} failed.\n";
exit($failures > 0 ? 1 : 0);
