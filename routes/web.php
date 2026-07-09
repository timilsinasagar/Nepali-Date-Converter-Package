<?php
// routes/web.php
//
// Loaded by NepaliDateServiceProvider only when config('nepali-date.demo_route_enabled')
// is true. Disabled by default so the package never adds routes to a
// production app without an explicit opt-in.

use Illuminate\Support\Facades\Route;
use Sagartimilsina\NepaliDate\Http\Controllers\NepaliDateDemoController;

Route::get(config('nepali-date.demo_route_path', '/nepali-date-demo'), [NepaliDateDemoController::class, 'index'])
    ->name('nepali-date.demo');

Route::post(config('nepali-date.demo_route_path', '/nepali-date-demo'), [NepaliDateDemoController::class, 'convert'])
    ->name('nepali-date.demo.convert');
