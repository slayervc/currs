<?php
declare(strict_types=1);

namespace App\Repository\CurrencyRate;


use App\DTO\Currency\CurrencyPair;
use App\DTO\Currency\CurrencyRateCollection;

interface CurrencyRateAggregateRepositoryInterface
{
    public function getAllByDateTimeRangeWithStep(
        CurrencyPair $currencyPair,
        \DateTimeInterface $from,
        ?\DateTimeInterface $to,
        int $stepSecs
    ): CurrencyRateCollection;
}