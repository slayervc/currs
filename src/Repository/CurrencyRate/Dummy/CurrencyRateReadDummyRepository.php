<?php
declare(strict_types=1);

namespace App\Repository\CurrencyRate\Dummy;


use App\DTO\Currency\CurrencyPair;
use App\DTO\Rate\CurrencyRate;
use App\DTO\Rate\CurrencyRateCollection;
use App\DTO\Rate\TimestampableRate;
use App\Repository\CurrencyRate\CurrencyRateReadRepositoryInterface;

class CurrencyRateReadDummyRepository implements CurrencyRateReadRepositoryInterface
{
    public function getCurrentRatesByCurrencyPairs(
        \DateTimeInterface $dateTime,
        CurrencyPair ...$pairs
    ): CurrencyRateCollection {
        $rates = [];
        foreach ($pairs as $pair) {
            $timestampableRate = new TimestampableRate($dateTime, rand(10000, 1000000) / 10000);
            $rates[] = new CurrencyRate($pair, $timestampableRate);
        }

        return new CurrencyRateCollection(...$rates);
    }
}