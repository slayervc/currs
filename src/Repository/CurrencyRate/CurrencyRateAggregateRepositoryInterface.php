<?php
declare(strict_types=1);

namespace App\Repository\CurrencyRate;


use App\DTO\Currency\CurrencyPair;
use App\DTO\Currency\CurrencyRateCollection;
use App\Repository\CurrencyRate\Exception\CurrencyRateAggregateRepositoryException;


interface CurrencyRateAggregateRepositoryInterface
{
    /**
     * @param  CurrencyPair            $currencyPair
     * @param  \DateTimeInterface      $from
     * @param  \DateTimeInterface|null $to
     * @param  int                     $stepSecs
     *
     * @return CurrencyRateCollection
     * @throws CurrencyRateAggregateRepositoryException
     */
    public function getAllByDateTimeRangeWithStep(
        CurrencyPair $currencyPair,
        \DateTimeInterface $from,
        ?\DateTimeInterface $to,
        int $stepSecs
    ): CurrencyRateCollection;
}