# Nepali Date Converter for PHP & Laravel

[![Latest Version](https://img.shields.io/packagist/v/sagartimilsina/nepali-date.svg)](https://packagist.org/packages/sagartimilsina/nepali-date)
[![PHP Version](https://img.shields.io/packagist/php-v/sagartimilsina/nepali-date.svg)](https://packagist.org/packages/sagartimilsina/nepali-date)
[![License](https://img.shields.io/packagist/l/sagartimilsina/nepali-date.svg)](LICENSE)

A lightweight, accurate, and developer-friendly PHP package for converting dates between **Bikram Sambat (BS)** and **Gregorian (AD)**.

Supports both **Laravel** and **plain PHP** applications.

---

## Features

- ✅ Convert **BS → AD**
- ✅ Convert **AD → BS**
- ✅ Accepts **English** and **Nepali (Devanagari)** numerals
- ✅ Format dates in English or Nepali
- ✅ Day of week support
- ✅ Month name support
- ✅ Laravel Auto Discovery
- ✅ Zero external API dependency
- ✅ Fast and lightweight
- ✅ PHP 8.2+

---

## Requirements

- PHP 8.2+
- Laravel 9, 10, 11, 12, 13 (optional)

---

## Installation

Install via Composer.

```bash
composer require sagartimilsina/nepali-date
```

Laravel will automatically discover the service provider.

---

# Basic Usage

```php
use Sagartimilsina\NepaliDate\Facades\NepaliDate;
```

---

## BS to AD

```php
$result = NepaliDate::bsToAd('2083-03-24');

echo $result;
```

Output

```text
2026-07-08
```

---

## AD to BS

```php
$result = NepaliDate::adToBs('2026-07-08');

echo $result;
```

Output

```text
2083-03-24
```

---

# Nepali Number Input

The package accepts Nepali digits.

```php
NepaliDate::bsToAd('२०८३-०३-२४');
```

---

# Nepali Output

```php
NepaliDate::adToBs(
    '2026-07-08',
    language: 'np'
);
```

Output

```text
२०८३-०३-२४
```

---

# Get Month Name

```php
NepaliDate::monthName(3);
```

Output

```text
Ashadh
```

Nepali

```php
NepaliDate::monthName(3, 'np');
```

Output

```text
असार
```

---

# Get Day Name

```php
NepaliDate::dayName('2026-07-08');
```

Output

```text
Wednesday
```

Nepali

```php
NepaliDate::dayName('2026-07-08', 'np');
```

Output

```text
बुधबार
```

---

# Format Date

```php
NepaliDate::format(
    '2083-03-24',
    language: 'en'
);
```

Output

```text
24 Ashadh 2083
```

Nepali

```php
NepaliDate::format(
    '2083-03-24',
    language: 'np'
);
```

Output

```text
२४ असार २०८३
```

---

# Plain PHP Usage

```php
require 'vendor/autoload.php';

use Sagartimilsina\NepaliDate\NepaliDate;

$date = new NepaliDate();

echo $date->bsToAd('2083-03-24');
```

---

# Supported Years

| Calendar | Supported Years |
|-----------|-----------------|
| BS | 2000 – 2100 |
| AD | 1943 – 2044 |

---

# Example

```php
use Sagartimilsina\NepaliDate\Facades\NepaliDate;

echo NepaliDate::adToBs('2026-07-08');

echo NepaliDate::bsToAd('2083-03-24');
```

---

# Testing

```bash
composer test
```

or

```bash
vendor/bin/phpunit
```

---

# Contributing

Contributions are welcome.

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push the branch
5. Open a Pull Request

---

# Roadmap

- [x] BS → AD Conversion
- [x] AD → BS Conversion
- [x] Nepali Number Support
- [x] Laravel Integration
- [ ] Carbon Integration
- [ ] Date Validation Helpers
- [ ] Blade Components
- [ ] Localization Improvements

---

# License

This package is open-sourced software licensed under the MIT License.

---

# Author

**Sagar Timilsina**

Laravel & Full Stack Developer

GitHub

https://github.com/timilsinasagar

Packagist

https://packagist.org/packages/sagartimilsina/nepali-date

---

If this package helps you, please consider giving it a ⭐ on GitHub.