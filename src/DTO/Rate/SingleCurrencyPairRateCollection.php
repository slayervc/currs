<?php
declare(strict_types=1);

namespace App\DTO\Rate;


use App\DTO\Currency\CurrencyPair;

class SingleCurrencyPairRateCollection
{
    private CurrencyPair $currencyPair;

    /**
     * @var TimestampableRate[]
     */
    private array $rates;

    public function __construct(CurrencyPair $currencyPair, TimestampableRate ...$rates)
    {
        $this->currencyPair = $currencyPair;
        $this->rates = $rates;
    }

    public function getCurrencyPair(): CurrencyPair
    {
        return $this->currencyPair;
    }

    public function getRates(): array
    {
        return $this->rates;
    }
}