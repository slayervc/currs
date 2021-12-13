<?php
declare(strict_types=1);

namespace App\DTO\Rate;


use App\DTO\Currency\CurrencyPair;

class CurrencyRate
{
    private CurrencyPair $pair;
    private TimestampableRate $timestampableRate;

    public function __construct(CurrencyPair $pair, TimestampableRate $rate)
    {
        $this->pair = $pair;
        $this->timestampableRate = $rate;
    }

    public function getPair(): CurrencyPair
    {
        return $this->pair;
    }

    public function getTimestampableRate(): TimestampableRate
    {
        return $this->timestampableRate;
    }
}