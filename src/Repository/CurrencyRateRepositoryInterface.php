<?php
declare(strict_types=1);

namespace App\Repository;


use App\DTO\Currency\CurrencyPair;
use App\DTO\Currency\CurrencyRateCollection;

interface CurrencyRateRepositoryInterface
{
    public function getAllByDateTimeRangeWithStep(
        CurrencyPair $currencyPair,
        \DateTimeInterface $from,
        ?\DateTimeInterface $to,
        int $stepSecs
    ): CurrencyRateCollection;
}