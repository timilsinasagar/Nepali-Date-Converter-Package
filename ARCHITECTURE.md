# Architecture & Flow

This document explains how the package actually works, layer by layer, in the order a request/call flows through it. It's meant to be readable without cross-referencing the source.

---

## 1. The core problem

Bikram Sambat (BS) and Gregorian (AD) calendars don't share a fixed day-count relationship — BS months vary in length (29–32 days) from year to year, with no simple formula. The only reliable way to convert between them is a **lookup table of how many days are in each BS month, for every supported year**, plus one fixed anchor point tying a specific BS date to a specific AD date.

Everything else in this package is built on top of that one table.

---

## 2. Layer-by-layer flow

```
                          ┌─────────────────────┐
                          │   CalendarData.php   │   The raw data: BS year => [12 month lengths]
                          │  (the source of      │   + one anchor point (BS 2000-01-01 = AD 1943-04-14)
                          │   truth)             │
                          └──────────┬───────────┘
                                     │
                          ┌──────────▼───────────┐
                          │  BsAdConverter.php    │   Pure day-counting math only.
                          │  (the engine)         │   adToBs(): counts days from the anchor to the
                          │                       │   target AD date, then walks the month-length
                          │                       │   table to land on a BS year/month/day.
                          │                       │   bsToAd(): does the same walk in reverse.
                          │                       │   No Carbon, no formatting, no names --
                          │                       │   just integers in, integers out.
                          └──────────┬───────────┘
                                     │
              ┌──────────────────────┼───────────────────────┐
              │                      │                        │
    ┌─────────▼─────────┐  ┌─────────▼──────────┐  ┌──────────▼─────────┐
    │  InputParser.php   │  │   NameData.php      │  │  (Carbon\Carbon)    │
    │  Turns messy BS    │  │   Static EN/NP       │  │  Handles all AD-side │
    │  input ("२०८२-०३-  │  │   translation tables │  │  date math: parsing, │
    │  २४", "24 Ashadh   │  │   for month names     │  │  "today", "now",     │
    │  2082") into clean  │  │   and weekday names.  │  │  timezone-aware       │
    │  {year, month, day} │  │   NOT a per-date       │  │  arithmetic.          │
    │  integers.           │  │   lookup -- just a     │  │                       │
    │                       │  │   translation           │  │                       │
    │                       │  │   dictionary.           │  │                       │
    └───────────┬───────────┘  └──────────┬─────────────┘  └───────────┬───────────┘
                │                          │                             │
                └──────────────┬───────────┴─────────────────────────────┘
                                │
                     ┌──────────▼───────────┐
                     │ NepaliDateManager.php │   The orchestrator. Combines the pieces above:
                     │  (the brain)          │   1. Parse/normalize whatever the caller passed in
                     │                       │   2. Run it through BsAdConverter for the raw
                     │                       │      year/month/day math
                     │                       │   3. Attach a real Carbon instance for the AD side
                     │                       │   4. Derive the weekday DIRECTLY from that Carbon
                     │                       │      instance (carbon->format('w')/('l')) --
                     │                       │      never from a static per-date table
                     │                       │   5. Package everything into a NepaliDateResult
                     └──────────┬────────────┘
                                │
                     ┌──────────▼────────────┐
                     │ NepaliDateResult.php   │   Immutable value object. Everything a caller
                     │  (the result)          │   would normally have to compute themselves,
                     │                        │   already bundled: year, month (number + both
                     │                        │   names), day, weekday (both languages),
                     │                        │   formatted strings, AND the Carbon instance
                     │                        │   so you can keep using Carbon's own API for
                     │                        │   anything this package doesn't cover.
                     └──────────┬─────────────┘
                                │
                ┌───────────────┼────────────────────┐
                │                                     │
     ┌──────────▼───────────┐               ┌─────────▼──────────┐
     │  NepaliDate facade    │               │  Plain-PHP           │
     │  (Laravel apps)        │               │  NepaliDate class     │
     │                         │               │  (no framework needed)│
     └──────────┬──────────────┘               └───────────────────────┘
                │
     ┌──────────▼───────────────┐
     │  NepaliDateService.php    │   Business logic for the BUNDLED DEMO only.
     │  (service layer)           │   Talks to NepaliDateManager, shapes results
     │                             │   into flat arrays the Blade view can render.
     │                             │   This is a template -- if you're building your
     │                             │   own app, write your own service layer the
     │                             │   same way rather than calling the facade
     │                             │   straight from your controllers.
     └──────────┬──────────────────┘
                │
     ┌──────────▼──────────────────┐      ┌────────────────────────┐
     │ NepaliDateDemoController.php │─────▶│ ConvertDateRequest.php  │
     │  Thin -- just wires the       │      │  Form Request: validates│
     │  Form Request + Service        │      │  direction/date_value    │
     │  Layer together, no logic       │      │  BEFORE the controller    │
     │  of its own.                     │      │  method body even runs.    │
     └──────────┬────────────────────────┘      └─────────────────────────┘
                │
     ┌──────────▼─────────┐
     │  demo.blade.php      │   Bootstrap 5 form + result panel.
     │  (the view)           │   Reads the flat array from the service layer,
     │                         │   nothing computed in the view itself.
     └─────────────────────┘
```

---

## 3. Why it's split this way

| Layer | Job | Why it's separate |
|---|---|---|
| `CalendarData` | Hold the raw month-length table | Data should never mix with logic — makes the table easy to audit/update on its own |
| `BsAdConverter` | Pure integer day-counting math | Testable in complete isolation, no dependency on Carbon or Laravel at all |
| `InputParser` | Normalize messy user input | Keeps "what does this string mean" separate from "how do I convert it" |
| `NameData` | Translation dictionaries | A dictionary lookup (Ashadh ↔ असार) is not the same kind of thing as calendar math — keeping them apart makes each easier to verify independently |
| `NepaliDateManager` | Orchestrate the above + attach Carbon | The one place that knows about all the other pieces, so nothing else has to |
| `NepaliDateResult` | Immutable, self-describing result | Once built, a result can't be silently mutated later in a request — and it carries its own AD-side Carbon instance so you rarely need to convert twice |
| `NepaliDateService` | Demo-specific business logic | Keeps the controller free of `if/else` conversion logic — this is the pattern to copy in your own app |
| `NepaliDateDemoController` | HTTP wiring only | A controller that does one thing (call the service, return a response) is trivial to read and trivial to test |
| `ConvertDateRequest` | Validation | Runs *before* the controller method body — invalid input never reaches business logic at all |

---

## 4. Why the weekday is never hardcoded

A tempting shortcut would be a static table like `['2083-03-24' => 'Wednesday', ...]`. That's wrong for two reasons:

1. It would need one entry per supported day (tens of thousands of rows) instead of one per calendar year.
2. Any error in the BS↔AD day-count table would silently propagate into a *second*, disconnected table — doubling the ways things can go wrong.

Instead, the weekday is computed **once, from the actual converted AD date**, at the moment a result is built:

```php
$weekdayIndex = (int) $carbon->format('w'); // 0 (Sun) .. 6 (Sat), derived from the real date
$weekdayEn    = $carbon->format('l');        // "Wednesday" -- straight from Carbon, no lookup at all
$weekdayNp    = NameData::dayName($weekdayIndex, true); // translation of that index, not a per-date fact
```

`NameData::$dayNamesNp` is a **7-row translation dictionary** ("Sunday" → "आइतबार", etc.) — not a calendar fact about any particular date. That distinction is what "don't hardcode weekday names" actually means in this codebase.

---

## 5. Where to plug in your own logic

- **Need a new derived field on the result?** Add it to `NepaliDateResult` and populate it in `NepaliDateManager::buildResult()` — everything downstream (facade, plain-PHP class, service layer) gets it automatically.
- **Building your own controller/service instead of the bundled demo?** Copy the pattern in `NepaliDateService` — inject `NepaliDateManager`, keep your controller thin, keep validation in a Form Request.
- **Need a different BS year range?** Everything reads from `CalendarData::$calendarData` — extend that table and the rest of the package adapts without changes elsewhere.
