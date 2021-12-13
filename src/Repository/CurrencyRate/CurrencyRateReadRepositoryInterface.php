<?php
declare(strict_types=1);

namespace App\Repository\CurrencyRate;


use App\DTO\Currency\CurrencyPair;
use App\DTO\Rate\CurrencyRateCollection;
use App\Repository\CurrencyRate\Exception\CurrencyRateReadRepositoryException;

interface CurrencyRateReadRepositoryInterface
{
    /**
     * @param  \DateTimeInterface $dateTime
     * @param  CurrencyPair       ...$pairs
     *
     * @return CurrencyRateCollection
     * @throws CurrencyRateReadRepositoryException
     */
    public function getCurrentRatesByCurrencyPairs(
        \DateTimeInterface $dateTime,
        CurrencyPair ...$pairs
    ): CurrencyRateCollection;
}