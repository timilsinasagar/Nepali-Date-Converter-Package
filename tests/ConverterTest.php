<?php
// tests/ConverterTest.php

namespace Sagartimilsina\NepaliDate\Tests;

use PHPUnit\Framework\TestCase;
use Sagartimilsina\NepaliDate\Converter\BsAdConverter;
use Sagartimilsina\NepaliDate\Converter\InputParser;

class ConverterTest extends TestCase
{
    public function test_ad_to_bs_conversion(): void
    {
        $converter = new BsAdConverter();
        $result = $converter->adToBs('1913-04-13'); // your table's day-1 reference
        $this->assertEquals(1970, $result['year']);
        $this->assertEquals(1, $result['month']);
        $this->assertEquals(1, $result['day']);
    }

    public function test_devanagari_input_parsing(): void
    {
        $parsed = InputParser::parseBsDate('२०८१-०३-१५');
        $this->assertEquals(['year' => 2081, 'month' => 3, 'day' => 15], $parsed);
    }
}