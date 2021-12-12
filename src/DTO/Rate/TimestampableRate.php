<?php
declare(strict_types=1);

namespace App\DTO\Rate;


class TimestampableRate
{
    private \DateTimeInterface $datetime;
    private float $rate;

    public function __construct(\DateTimeInterface $datetime, float $rate)
    {
        $this->datetime = $datetime;
        $this->rate = $rate;
    }

    public function getDatetime(): \DateTimeInterface
    {
        return $this->datetime;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}