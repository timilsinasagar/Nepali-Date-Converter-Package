<?php
// src/Facades/NepaliDate.php

namespace Sagartimilsina\NepaliDate\Facades;

use Illuminate\Support\Facades\Facade;

class NepaliDate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'nepali-date';
    }
}