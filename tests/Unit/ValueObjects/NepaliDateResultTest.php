<?php

namespace Sagartimilsina\NepaliDate\Tests\Unit\ValueObjects;

use PHPUnit\Framework\TestCase;
use Sagartimilsina\NepaliDate\NepaliDate;

class NepaliDateResultTest extends TestCase
{
    public function test_to_date_string_and_devanagari_date_string(): void
    {
        $result = (new NepaliDate())->adToBs('2026-07-08');

        $this->assertSame('2083-03-24', $result->toDateString());
        $this->assertSame('२०८३-०३-२४', $result->toDevanagariDateString());
    }

    public function test_string_cast_returns_formatted_english_date(): void
    {
        $result = (new NepaliDate())->adToBs('2026-07-08');

        $this->assertSame('24 Ashadh 2083', (string) $result);
    }

    public function test_readonly_properties_cannot_be_reassigned(): void
    {
        $result = (new NepaliDate())->adToBs('2026-07-08');

        $this->expectException(\Error::class);

        // @phpstan-ignore-next-line -- intentionally testing readonly enforcement
        $result->year = 2000;
    }
}
