# Nepali Date Converter for PHP & Laravel

[![Latest Version](https://img.shields.io/packagist/v/sagartimilsina/nepali-date.svg)](https://packagist.org/packages/sagartimilsina/nepali-date)
[![PHP Version](https://img.shields.io/packagist/php-v/sagartimilsina/nepali-date.svg)](https://packagist.org/packages/sagartimilsina/nepali-date)
[![License](https://img.shields.io/packagist/l/sagartimilsina/nepali-date.svg)](LICENSE)

A lightweight, accurate, and developer-friendly PHP package for converting dates between **Bikram Sambat (BS)** and **Gregorian (AD)**.

Works in **plain PHP** and in **Laravel** (auto-discovered service provider + facade).

---

## Features

- ✅ Convert **BS → AD**
- ✅ Convert **AD → BS**
- ✅ Accepts **English** and **Nepali (Devanagari)** numerals as input
- ✅ Accepts flexible BS input: `"2082-03-24"`, `"२०८२-०३-२४"`, or `"24 Ashadh 2082"` / `"24 असार 2082"`
- ✅ Format a BS date as full text, in English or Nepali
- ✅ Month name lookup (English & Devanagari)
- ✅ Day-of-week name lookup (English & Devanagari)
- ✅ Day-number formatting (English & Devanagari digits)
- ✅ `today()` helper — current date converted to BS
- ✅ Laravel auto-discovery (service provider + `NepaliDate` facade)
- ✅ Zero external API dependency — pure PHP, fully offline
- ✅ Input validation with clear exceptions for out-of-range or malformed dates
- ✅ PHP 8.2+

---

## Requirements

- PHP 8.2+
- Laravel 9, 10, 11, 12 (optional — the package works standalone too)

---

## Installation

Install via Composer:

```bash
composer require sagartimilsina/nepali-date
```

Laravel will automatically discover the service provider and the `NepaliDate` facade — no manual registration needed.

If you'd like to tweak the config file, publish it with:

```bash
php artisan vendor:publish --tag=nepali-date-config
```

---

## Basic Usage (Laravel)

```php
use Sagartimilsina\NepaliDate\Facades\NepaliDate;
```

### BS to AD

```php
$result = NepaliDate::bsToAd('2083-03-24');

echo $result;
```

Output

```text
2026-07-08
```

### AD to BS

```php
$result = NepaliDate::adToBs('2026-07-08');

echo $result;
```

Output

```text
2083-03-24
```

### AD to BS — Devanagari output

```php
NepaliDate::adToBs('2026-07-08', true);
```

Output

```text
२०८३-०३-२४
```

### Nepali number input

The package accepts Devanagari digits directly, no separate conversion needed:

```php
NepaliDate::bsToAd('२०८३-०३-२४');
```

### Month-name style input

`bsToAd()` also understands day/month-name/year text in either language:

```php
NepaliDate::bsToAd('24 Ashadh 2083');
NepaliDate::bsToAd('24 असार 2083');
```

Both return `2026-07-08`.

---

## Month Names

```php
NepaliDate::getMonth(3);
```

Output

```text
Ashadh
```

Nepali:

```php
NepaliDate::getMonth(3, true);
```

Output

```text
असार
```

`monthName()` is available as an alias with the same signature.

---

## Day Numbers

```php
NepaliDate::getDay(15);
```

Output

```text
15
```

Nepali:

```php
NepaliDate::getDay(15, true);
```

Output

```text
१५
```

---

## Day-of-Week Names

`getDayName()` (and its alias `dayName()`) expects an **AD** date. If you have a BS date, convert it with `bsToAd()` first.

```php
NepaliDate::getDayName('2026-07-08');
```

Output

```text
Wednesday
```

Nepali:

```php
NepaliDate::getDayName('2026-07-08', true);
```

Output

```text
बुधबार
```

Combining with a BS date:

```php
$ad = NepaliDate::bsToAd('2083-03-24');
NepaliDate::getDayName($ad); // Wednesday
```

---

## Full Formatted Date

`format()` renders a BS date as readable text:

```php
NepaliDate::format('2083-03-24');
```

Output

```text
24 Ashadh 2083
```

Nepali:

```php
NepaliDate::format('2083-03-24', true);
```

Output

```text
२४ असार २०८३
```

`bsToNepaliText()` is a convenience shortcut equivalent to `format($bsDate, true)`.

---

## Today, in BS

```php
NepaliDate::today();       // e.g. "2083-03-24"
NepaliDate::today(true);   // e.g. "२०८३-०३-२४"
```

---

## Supported Year Range

```php
NepaliDate::getSupportedYearRange();
// [2000, 2100]
```

| Calendar | Supported Years                                         |
| -------- | ------------------------------------------------------- |
| BS       | 2000 – 2100                                             |
| AD       | 1943 – 2044 (approximate, driven by the BS range above) |

Dates outside this range throw an `InvalidArgumentException`.

---

## Plain PHP Usage (no Laravel required)

```php
require 'vendor/autoload.php';

use Sagartimilsina\NepaliDate\NepaliDate;

$date = new NepaliDate();

echo $date->bsToAd('2083-03-24');   // 2026-07-08
echo $date->adToBs('2026-07-08');   // 2083-03-24
echo $date->format('2083-03-24');   // 24 Ashadh 2083
echo $date->getMonth(3, true);      // असार
echo $date->getDayName('2026-07-08'); // Wednesday
```

---

## Full API Reference

| Method                   | Signature                                            | Description                                                       |
| ------------------------ | ---------------------------------------------------- | ----------------------------------------------------------------- |
| `adToBs`                 | `(string $adDate, bool $devanagari = false): string` | Convert AD → BS.                                                  |
| `bsToAd`                 | `(string $bsDate): string`                           | Convert BS → AD. Accepts numeric, Devanagari, or month-name text. |
| `bsToNepaliText`         | `(string $bsDate): string`                           | Shortcut for `format($bsDate, true)`.                             |
| `format`                 | `(string $bsDate, bool $devanagari = false): string` | Full formatted date, e.g. `"24 Ashadh 2083"`.                     |
| `getMonth` / `monthName` | `(int $month, bool $devanagari = false): string`     | BS month name from its 1–12 index.                                |
| `getDay`                 | `(int $day, bool $devanagari = false): string`       | Day number formatted in English or Devanagari digits.             |
| `getDayName` / `dayName` | `(string $adDate, bool $devanagari = false): string` | Weekday name for an **AD** date.                                  |
| `today`                  | `(bool $devanagari = false): string`                 | Current date converted to BS.                                     |
| `getSupportedYearRange`  | `(): array`                                          | `[minYear, maxYear]` supported BS years.                          |

All methods are available on the `NepaliDate` facade (Laravel), the plain-PHP `NepaliDate` class, and directly on `NepaliDateManager` if you resolve it from the container yourself.

---

## Error Handling

Invalid input throws `InvalidArgumentException` (or `OutOfRangeException` for missing calendar-table years):

```php
try {
    NepaliDate::bsToAd('not-a-date');
} catch (\InvalidArgumentException $e) {
    // "Unable to parse BS date: not-a-date"
}
```

---

## Example: Full Demo Controller

A working Laravel controller + Blade form that exercises every method in this README is included under [`examples/Laravel`](examples/Laravel) — copy the controller into `app/Http/Controllers`, the view into `resources/views`, and wire up the routes in `examples/Laravel/routes-snippet.php`.

---

## Testing

The package ships with a PHPUnit suite:

```bash
composer install
composer test
```

or directly:

```bash
vendor/bin/phpunit
```

If you don't have Composer/PHPUnit set up yet, `tests/manual_verify.php` runs the same core assertions with zero dependencies:

```bash
php tests/manual_verify.php
```

---

## Contributing

Contributions are welcome.

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push the branch
5. Open a Pull Request

---

## Roadmap

- [x] BS → AD Conversion
- [x] AD → BS Conversion
- [x] Nepali Number Support
- [x] Laravel Integration
- [x] Month / Day-of-week name helpers
- [x] Full-text date formatting
- [ ] Carbon integration (`toCarbon()` / `fromCarbon()`)
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
