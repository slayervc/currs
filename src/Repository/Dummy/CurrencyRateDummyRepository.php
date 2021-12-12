<?php
declare(strict_types=1);

namespace App\Repository\Dummy;


use App\DTO\Currency\CurrencyPair;
use App\DTO\Currency\CurrencyRateCollection;
use App\DTO\Rate\TimestampableRate;
use App\Repository\CurrencyRateRepositoryInterface;

class CurrencyRateDummyRepository implements CurrencyRateRepositoryInterface
{
    public function getAllByDateTimeRangeWithStep(
        CurrencyPair $currencyPair,
        \DateTimeInterface $from,
        ?\DateTimeInterface $to,
        int $stepSecs
    ): CurrencyRateCollection {
        $now = new \DateTimeImmutable();
        $rates = [
            new TimestampableRate($now, 10),
            new TimestampableRate($now->sub(new \DateInterval('PT1S')), 15),
            new TimestampableRate($now->sub(new \DateInterval('PT2S')), 20),
            new TimestampableRate($now->sub(new \DateInterval('PT3S')), 25),
        ];

        return new CurrencyRateCollection($currencyPair, ...$rates);
    }

}