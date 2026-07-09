# Changelog

All notable changes to this package are documented here. This project follows [Semantic Versioning](https://semver.org/).

## [2.0.0] — Unreleased

### Breaking Changes

- **`adToBs()` now returns a `NepaliDateResult` object, not a string.** It carries `year`, `month`, `monthNameEn`/`monthNameNp`, `day`, `weekdayEn`/`weekdayNp`, `formattedEn`/`formattedNp`, and a `carbon` (AD) instance, plus `toDateString()`, `toDevanagariDateString()`, `toArray()`, and `__toString()` (returns `formattedEn`).
- **`bsToAd()` now returns a `Carbon\Carbon` instance, not a string.** Call `->format('Y-m-d')` on the result if you need a string.
- The old string-in/string-out behavior is preserved under new names: **`adToBsString()`** and **`bsToAdString()`**. Existing v1.x code that needs plain strings should switch to these.

### Added

- `toCarbon(string $bsDate): Carbon` — explicit alias of `bsToAd()`.
- `todayBS(): NepaliDateResult` / `todayAD(): Carbon`
- `nowBS(): NepaliDateResult` / `nowAD(): Carbon` (includes current time on the attached Carbon instance)
- `NepaliDateResult` value object (`src/ValueObjects/NepaliDateResult.php`), immutable (`readonly` properties), `JsonSerializable`.
- `adToBs()` now accepts a `Carbon` instance or any `DateTimeInterface`, not just strings.
- Weekday names are derived live from the converted date's actual day-of-week (via Carbon) rather than a static per-date table. English comes directly from Carbon; Nepali is a 7-entry translation dictionary keyed by weekday index, not by date.
- `NepaliDateService` (`src/Services/NepaliDateService.php`) — service layer for the bundled demo; also serves as the recommended pattern for consuming apps.
- `ConvertDateRequest` (`src/Http/Requests/ConvertDateRequest.php`) — strict Form Request validation for the demo endpoint.
- `NepaliDateDemoController` + Bootstrap 5 `demo.blade.php` — bundled, responsive conversion demo. Mounted at `config('nepali-date.demo_route_path')` (default `/nepali-date-demo`) only when `config('nepali-date.demo_route_enabled')` is `true` (default `false` — the package never adds a route to your app without explicit opt-in).
- `nepali-date.demo_route_enabled` / `NEPALI_DATE_DEMO_ROUTE` env var.
- `nepali-date.demo_route_path` / `NEPALI_DATE_DEMO_PATH` env var.
- `ARCHITECTURE.md` — explains the internal flow and design decisions in plain terms.
- Laravel Pint config (`pint.json`), PSR-12 preset.
- `composer test` / `composer lint` / `composer format` scripts.

### Changed

- `composer.json` now requires `nesbot/carbon` explicitly (previously an incidental dependency via `illuminate/support`).
- Support widened to Laravel 10, 11, 12, and 13 (`illuminate/support: ^10.0 || ^11.0 || ^12.0 || ^13.0`).
- `require-dev` now includes `orchestra/testbench` for Laravel-context testing, and `laravel/pint`.

### Unchanged (still available, same signatures)

`format()`, `getMonth()` / `monthName()`, `getDay()`, `getDayName()` / `dayName()`, `bsToNepaliText()`, `today()`, `getSupportedYearRange()`.

### Data Accuracy

No changes to `CalendarData.php` in this release. It remains cross-checked against an independent third-party BS/AD converter at 7 points spanning BS 2060–2083 — see README "Data Accuracy" section. BS 2091–2100 remains unverified against an independent source; treat with caution.

---

## [1.1.0]

### Added

- `NameData` class: centralized month-name and weekday-name lookup tables (English + Devanagari), replacing inline arrays.
- Externally-verified reference-date regression tests (`tests/NepaliDateTest.php::test_bs_to_ad_matches_verified_reference_dates`), cross-checked against a third-party BS/AD converter at 7 points spanning BS 2060–2083.
- `tests/manual_verify.php` — zero-dependency verification script for environments without Composer/PHPUnit set up.
- `format()`, `getMonth()`, `getDay()`, `getDayName()`, `monthName()`, `dayName()`, `today()`, `getSupportedYearRange()` helper methods.
- `bsToAd()` accepts flexible input: numeric (`"2082-03-24"`), Devanagari digits, or month-name text (`"24 Ashadh 2082"` / `"24 असार 2082"`).

### Fixed

- `getDayName()` no longer guesses whether its input is an AD or BS date. It now always expects an AD date — the previous auto-detect logic could silently return the wrong weekday for a valid AD date that also happened to match the BS numeric pattern.

---

## [1.0.0]

### Added

- Initial release.
- `adToBs(string $adDate, bool $devanagari = false): string`
- `bsToAd(string $bsDate): string`
- `bsToNepaliText(string $bsDate): string`
- Laravel auto-discovery (service provider + `NepaliDate` facade).
- BS calendar data table, years 2000–2100.
