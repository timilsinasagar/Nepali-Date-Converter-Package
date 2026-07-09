# Nepali Date Converter for PHP & Laravel

[![Latest Version](https://img.shields.io/packagist/v/sagartimilsina/nepali-date.svg)](https://packagist.org/packages/sagartimilsina/nepali-date)
[![PHP Version](https://img.shields.io/packagist/php-v/sagartimilsina/nepali-date.svg)](https://packagist.org/packages/sagartimilsina/nepali-date)
[![License](https://img.shields.io/packagist/l/sagartimilsina/nepali-date.svg)](LICENSE)

A production-ready, Carbon-backed PHP package for converting dates between **Bikram Sambat (BS)** and **Gregorian (AD)**.

Works in **plain PHP** and in **Laravel 10, 11, 12, and 13** (auto-discovered service provider + facade).

> **Upgrading from v1.x?** `adToBs()` and `bsToAd()` now return rich objects instead of plain strings — see [CHANGELOG.md](CHANGELOG.md) for the full breaking-change notes and the `*String()` methods that preserve the old behavior.

---

## Features

- ✅ Convert **AD → BS** and **BS → AD**, returning a full result object (year, month number + name in both languages, day, weekday in both languages, formatted text, and a `Carbon` instance) — not just a string
- ✅ `todayBS()` / `todayAD()` / `nowBS()` / `nowAD()` helpers
- ✅ `toCarbon()` — go straight from a BS date string to a `Carbon` instance
- ✅ Accepts **English** and **Nepali (Devanagari)** numerals as input
- ✅ Accepts flexible BS input: `"2082-03-24"`, `"२०८२-०३-२४"`, or `"24 Ashadh 2082"` / `"24 असार 2082"`
- ✅ Weekday names are **derived live** from the converted date (via Carbon), never hardcoded per-date
- ✅ Laravel auto-discovery (service provider + `NepaliDate` facade)
- ✅ Strict Form Request validation + Service Layer + Bootstrap 5 demo, bundled and opt-in
- ✅ Zero external API dependency — pure PHP + Carbon, fully offline
- ✅ PSR-12 compliant (Laravel Pint), PHPUnit test suite
- ✅ PHP 8.2+

---

## Requirements

- PHP 8.2+
- `nesbot/carbon` ^2.72 || ^3.0 (already a dependency of every Laravel app)
- Laravel 10, 11, 12, or 13 (optional — the package works standalone too)

---

## Installation

```bash
composer require sagartimilsina/nepali-date
```

Laravel auto-discovers the service provider and the `NepaliDate` facade — no manual registration needed.

Publish the config file if you want to tweak defaults:

```bash
php artisan vendor:publish --tag=nepali-date-config
```

---

## Quick Start (Laravel)

```php
use Sagartimilsina\NepaliDate\Facades\NepaliDate;

$result = NepaliDate::adToBs('2026-07-08');

$result->year;          // 2083
$result->month;         // 3
$result->day;            // 24
$result->monthNameEn;    // "Ashadh"
$result->monthNameNp;    // "असार"
$result->weekdayEn;      // "Wednesday"
$result->weekdayNp;      // "बुधबार"
$result->formattedEn;    // "24 Ashadh 2083"
$result->formattedNp;    // "२४ असार २०८३"
$result->toDateString(); // "2083-03-24"
$result->carbon;         // Carbon instance for 2026-07-08
(string) $result;        // "24 Ashadh 2083" (same as formattedEn)
```

```php
$carbon = NepaliDate::bsToAd('2083-03-24');

$carbon->format('Y-m-d');   // "2026-07-08"
$carbon->diffForHumans();   // any normal Carbon method works
```

---

## API Reference

### Primary API (v2.0.0)

| Method | Signature | Returns |
|---|---|---|
| `adToBs` | `(string\|DateTimeInterface $adDate)` | `NepaliDateResult` |
| `bsToAd` | `(string $bsDate)` | `Carbon` |
| `toCarbon` | `(string $bsDate)` | `Carbon` — alias of `bsToAd()` |
| `todayBS` | `()` | `NepaliDateResult` for today |
| `todayAD` | `()` | `Carbon` for today (midnight) |
| `nowBS` | `()` | `NepaliDateResult` for the current moment |
| `nowAD` | `()` | `Carbon` for the current moment (includes time) |
| `getSupportedYearRange` | `()` | `array` — `[minYear, maxYear]` |

### `NepaliDateResult` properties & methods

| Member | Type |
|---|---|
| `$year`, `$month`, `$day` | `int` |
| `$monthNameEn`, `$monthNameNp` | `string` |
| `$dayEn`, `$dayNp` | `string` (day number, formatted in each script) |
| `$weekdayEn`, `$weekdayNp` | `string` |
| `$formattedEn`, `$formattedNp` | `string` — e.g. `"24 Ashadh 2083"` / `"२४ असार २०८३"` |
| `$carbon` | `Carbon` — the equivalent AD instant |
| `toDateString()` | `string` — `"2083-03-24"` |
| `toDevanagariDateString()` | `string` — `"२०८३-०३-२४"` |
| `toArray()` / `jsonSerialize()` | `array` |
| `__toString()` | same as `$formattedEn` |

### Legacy string-based API (v1.x, still supported)

These return plain strings, exactly like v1.x:

| Method | Signature |
|---|---|
| `adToBsString` | `(string $adDate, bool $devanagari = false): string` |
| `bsToAdString` | `(string $bsDate): string` |
| `bsToNepaliText` | `(string $bsDate): string` |
| `format` | `(string $bsDate, bool $devanagari = false): string` |
| `getMonth` / `monthName` | `(int $month, bool $devanagari = false): string` |
| `getDay` | `(int $day, bool $devanagari = false): string` |
| `getDayName` / `dayName` | `(string $adDate, bool $devanagari = false): string` |
| `today` | `(bool $devanagari = false): string` |

All methods above are available on the `NepaliDate` facade (Laravel), the plain-PHP `NepaliDate` class, and directly on `NepaliDateManager` if you resolve it from the container yourself.

---

## Plain PHP Usage (no Laravel required)

```php
require 'vendor/autoload.php';

use Sagartimilsina\NepaliDate\NepaliDate;

$date = new NepaliDate();

$result = $date->adToBs('2026-07-08');
echo $result->formattedEn;              // "24 Ashadh 2083"

$carbon = $date->bsToAd('2083-03-24');
echo $carbon->format('Y-m-d');          // "2026-07-08"

echo $date->todayBS()->formattedNp;     // e.g. "२४ असार २०८३"
```

---

## Bundled Demo (Bootstrap 5)

A responsive AD↔BS conversion form ships with the package, built with a Form Request + Service Layer + thin controller (see [ARCHITECTURE.md](ARCHITECTURE.md)). It's **disabled by default** — the package never adds a route to your app without explicit opt-in.

Enable it in `.env`:

```env
NEPALI_DATE_DEMO_ROUTE=true
```

or in `config/nepali-date.php` after publishing it:

```php
'demo_route_enabled' => true,
'demo_route_path' => '/nepali-date-demo',
```

Then visit `/nepali-date-demo` (or whatever path you configured).

**Don't leave this enabled in production** — it's meant for local exploration and as a reference implementation to copy from.

---

## Error Handling

Invalid input throws `InvalidArgumentException` (or `OutOfRangeException` for years missing from the calendar table):

```php
try {
    NepaliDate::bsToAd('not-a-date');
} catch (\InvalidArgumentException $e) {
    // "Unable to parse BS date: not-a-date"
}
```

`ConvertDateRequest` (used by the bundled demo) validates `direction` and `date_value` *before* any conversion logic runs, so malformed HTTP input never reaches the service layer at all.

---

## Data Accuracy

The BS↔AD day-count table (`src/Converter/CalendarData.php`) has been cross-checked against a third-party BS/AD converter (englishtonepali.com) at 7 independent points spanning BS 2060–2083, including Nepali New Year dates and mid-month dates — all matched exactly, including day-of-week. These checks run as part of the test suite (`test_weekday_is_derived_from_the_converted_date` in `tests/Unit/NepaliDateManagerTest.php`), not as a one-off spot check.

Two things worth knowing:

- **BS 2000–2090 or so** is the range most public converters also cover, which is what made cross-checking possible.
- **BS 2091–2100** (the tail of the table) hasn't been independently verified the same way — treat conversions in that range with a bit more caution.

If you spot a mismatch anywhere in the table, please open an issue with the specific BS date and a source for the correct AD equivalent.

---

## Testing

```bash
composer install
composer test
```

or directly:

```bash
vendor/bin/phpunit
```

Zero-dependency verification (no Composer/PHPUnit needed — uses a local Carbon stub for the sandbox-style testing this package was originally verified with):

```bash
php tests/manual_verify.php
```

---

## Code Style

PSR-12 via Laravel Pint:

```bash
composer lint     # check only
composer format   # auto-fix
```

---

## Architecture

See [ARCHITECTURE.md](ARCHITECTURE.md) for a full walkthrough of how a conversion flows through the package, layer by layer, and why it's structured the way it is.

---

## Versioning & Changelog

This package follows [Semantic Versioning](https://semver.org/). See [CHANGELOG.md](CHANGELOG.md) for the full version history, including the v2.0.0 breaking changes.

---

## Contributing

1. Fork the repository
2. Create a feature branch
3. Run `composer lint` and `composer test` before committing
4. Commit your changes
5. Push the branch
6. Open a Pull Request

---

## Roadmap

- [x] BS → AD / AD → BS conversion
- [x] Nepali number support
- [x] Laravel integration
- [x] Month / weekday name helpers
- [x] Full-text date formatting
- [x] Carbon integration
- [x] Form Request + Service Layer + Bootstrap 5 demo
- [ ] Blade components (`<x-nepali-date>`)
- [ ] Nepali date picker JS/Alpine widget
- [ ] Locale-aware ordinal day formatting

---

## License

This package is open-sourced software licensed under the [MIT License](LICENSE).

---

## Author

**Sagar Timilsina**
Laravel & Full Stack Developer

- GitHub: [https://github.com/timilsinasagar](https://github.com/timilsinasagar)
- Packagist: [https://packagist.org/packages/sagartimilsina/nepali-date](https://packagist.org/packages/sagartimilsina/nepali-date)

If this package helps you, please consider giving it a ⭐ on GitHub.