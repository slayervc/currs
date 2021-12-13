<?php
declare(strict_types=1);

namespace App\Repository\CurrencyRate;


use App\DTO\Rate\CurrencyRate;
use App\Repository\CurrencyRate\Exception\CurrencyRateWriteRepositoryException;

interface CurrencyRateWriteRepositoryInterface
{
    /**
     * @param  CurrencyRate ...$rates
     *
     * @throws CurrencyRateWriteRepositoryException
     */
    public function storeRates(CurrencyRate ...$rates): void;
}