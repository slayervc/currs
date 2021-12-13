<?php
declare(strict_types=1);

namespace App\DTO\Rate;


use App\DTO\Currency\CurrencyPair;

class CurrencyRateCollection
{
    /**
     * @var array<string, CurrencyRate>
     */
    private array $currencyRates;

    public function __construct(CurrencyRate ...$currencyRates)
    {
        foreach ($currencyRates as $currencyRate) {
            $pairKey = $this->getPairKey($currencyRate->getPair());
            $this->currencyRates[$pairKey] = $currencyRate;
        }
    }

    public function getByPair(CurrencyPair $currencyPair): ?CurrencyRate
    {
        return $this->currencyRates[$this->getPairKey($currencyPair)] ?? null;
    }

    /**
     * @return array<string, CurrencyRate>
     */
    public function getAll(): array
    {
        return array_values($this->currencyRates);
    }

    private function getPairKey(CurrencyPair $currencyPair): string
    {
        return $currencyPair->getBase() . $currencyPair->getQuote();
    }
}