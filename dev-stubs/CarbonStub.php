<?php
// dev-stubs/CarbonStub.php
//
// FOR LOCAL SANDBOX TESTING ONLY. This is a minimal stand-in for
// Carbon\Carbon so tests/manual_verify_v2.php can run without a full
// Laravel + nesbot/carbon install. It is NOT shipped in src/ and is NOT
// part of the actual package -- real consumers get the real Carbon,
// which already ships with every Laravel app.

namespace Carbon;

class Carbon extends \DateTime
{
    public static function parse(string $time): static
    {
        return new static($time);
    }

    public static function today(): static
    {
        return new static(date('Y-m-d'));
    }

    public static function now(): static
    {
        return new static('now');
    }

    public function copy(): static
    {
        return clone $this;
    }

    public function startOfDay(): static
    {
        $this->setTime(0, 0, 0);

        return $this;
    }

    public function toDateString(): string
    {
        return $this->format('Y-m-d');
    }
}
