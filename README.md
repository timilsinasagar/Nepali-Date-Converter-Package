# Nepali Date Converter for PHP & Laravel

[![Latest Version](https://img.shields.io/packagist/v/sagartimilsina/nepali-date.svg)](https://packagist.org/packages/sagartimilsina/nepali-date)
[![PHP Version](https://img.shields.io/packagist/php-v/sagartimilsina/nepali-date.svg)](https://packagist.org/packages/sagartimilsina/nepali-date)
[![License](https://img.shields.io/packagist/l/sagartimilsina/nepali-date.svg)](LICENSE)

This package converts dates between **Nepali (BS)** and **English (AD)** calendars.

It works in plain PHP and also in Laravel (versions 10, 11, 12, and 13). If you use Laravel, it sets itself up automatically — you don't need to register anything.

> **Coming from version 1.x?** In version 2.0.0, `adToBs()` and `bsToAd()` now give you a full object with lots of details, instead of just a plain text string. See [CHANGELOG.md](CHANGELOG.md) if you need the old string-only behavior — it's still there under different method names.

---

## What this package can do

- Convert **AD date → BS date**, and **BS date → AD date**
- Give you the year, month, day, weekday — in both English and Nepali
- Give you today's date in BS or AD instantly (`todayBS()`, `todayAD()`)
- Accept dates typed in English numbers (2082) or Nepali numbers (२०८२)
- Accept BS dates in different formats, like `"2082-03-24"` or `"24 Ashadh 2082"`
- Work completely offline — no internet or external API needed
- Come with a ready-made demo page you can turn on to test it visually

---

## What you need before installing

- PHP 8.2 or newer
- Laravel 10, 11, 12, or 13 (optional — you can also use this without Laravel)

---

## How to install it

Run this command in your project folder:

```bash
composer require sagartimilsina/nepali-date
```

That's it — if you're using Laravel, it's ready to use right away.

If you want to change any settings later, publish the config file:

```bash
php artisan vendor:publish --tag=nepali-date-config
```

---

## How to use it (Laravel)

**Convert an English date to a Nepali date:**

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
```

**Convert a Nepali date to an English date:**

```php
$carbon = NepaliDate::bsToAd('2083-03-24');

$carbon->format('Y-m-d');   // "2026-07-08"
```

---

## All the available methods

### Main methods (recommended)

| Method | What you give it | What you get back |
|---|---|---|
| `adToBs` | an AD date | full details (year, month, day, weekday, etc.) |
| `bsToAd` | a BS date | an AD date (Carbon object) |
| `toCarbon` | a BS date | same as `bsToAd()` |
| `todayBS` | nothing | today's date in BS, with full details |
| `todayAD` | nothing | today's date in AD |
| `nowBS` | nothing | the current BS date and time |
| `nowAD` | nothing | the current AD date and time |
| `getSupportedYearRange` | nothing | the earliest and latest year this package supports |

### What you get back from `adToBs()`

| You can access | What it means |
|---|---|
| `$year`, `$month`, `$day` | the numbers |
| `$monthNameEn`, `$monthNameNp` | month name in English / Nepali |
| `$weekdayEn`, `$weekdayNp` | day of week in English / Nepali |
| `$formattedEn`, `$formattedNp` | ready-to-display text, e.g. "24 Ashadh 2083" |
| `$carbon` | the matching English date, as a normal Carbon date |
| `toDateString()` | gives you "2083-03-24" |

### Old methods from version 1.x (still work, just simpler output)

If you're on an older version and don't want to change your code, these still work and just return plain text instead of a full object:

`adToBsString`, `bsToAdString`, `bsToNepaliText`, `format`, `getMonth`, `getDay`, `getDayName`, `today`

---

## Using it without Laravel

```php
require 'vendor/autoload.php';

use Sagartimilsina\NepaliDate\NepaliDate;

$date = new NepaliDate();

$result = $date->adToBs('2026-07-08');
echo $result->formattedEn;   // "24 Ashadh 2083"

$carbon = $date->bsToAd('2083-03-24');
echo $carbon->format('Y-m-d');   // "2026-07-08"
```

---

## Try it out with the built-in demo page

This package includes a ready-made web page where you can type a date and see it converted instantly. It's **turned off by default** for safety — it won't show up on your site unless you turn it on.

To turn it on, add this to your `.env` file:

```env
NEPALI_DATE_DEMO_ROUTE=true
```

Then visit `/nepali-date-demo` in your browser.

⚠️ **Remember to turn this off before going live** — it's only meant for testing on your own computer.

---

## What happens if someone types an invalid date?

The package will stop and throw an error instead of silently giving a wrong answer:

```php
try {
    NepaliDate::bsToAd('not-a-date');
} catch (\InvalidArgumentException $e) {
    // "Unable to parse BS date: not-a-date"
}
```

If someone submits the demo form with bad data, it gets rejected before any conversion is even attempted.

---

## How accurate is it?

The date table this package uses has been checked against another trusted Nepali date converter website, at several points between BS 2060 and 2083 — including New Year dates. All of them matched, including the correct day of the week.

Two honest notes:

- Years **BS 2000–2090** are well-checked and safe to trust.
- Years **BS 2091–2100** (the very end of the range) haven't been double-checked as thoroughly — use with a little more caution if you're working in that range.

Found a date that looks wrong? Please open an issue on GitHub with the date and a source, and it'll get fixed.

---

## Running the tests

```bash
composer install
composer test
```

Or run PHPUnit directly:

```bash
vendor/bin/phpunit
```

---

## Code style check

```bash
composer lint     # just checks
composer format   # auto-fixes formatting
```

---

## Want to understand how it works internally?

See [ARCHITECTURE.md](ARCHITECTURE.md) — it explains step by step what happens when you convert a date, and why the code is organized the way it is.

---

## Version history

This package follows normal version numbering rules (Semantic Versioning). Full history is in [CHANGELOG.md](CHANGELOG.md).

---

## Want to contribute?

1. Fork this repository
2. Create a new branch for your change
3. Run `composer lint` and `composer test` — make sure both pass
4. Commit your change
5. Push your branch
6. Open a Pull Request

---

## What's planned next

- [x] BS ↔ AD conversion
- [x] Nepali number support
- [x] Laravel integration
- [x] Month / weekday names
- [x] Full text date formatting
- [x] Carbon integration
- [x] Demo page
- [ ] Blade component (`<x-nepali-date>`)
- [ ] A visual Nepali date picker
- [ ] Better ordinal day formatting (1st, 2nd, 3rd...)

---

## License

Free to use — [MIT License](LICENSE).

---

## Author

**Sagar Timilsina** — Laravel & Full Stack Developer

- GitHub: [https://github.com/timilsinasagar](https://github.com/timilsinasagar)
- Packagist: [https://packagist.org/packages/sagartimilsina/nepali-date](https://packagist.org/packages/sagartimilsina/nepali-date)

If this package was useful to you, a ⭐ on GitHub is always appreciated.