<?php
declare(strict_types=1);

namespace App\DTO\Currency\Response;


use App\DTO\Currency\CurrencyRateCollection;

class CurrencyPairGraphResponse
{
    private ?string $base = null;
    private ?string $quote = null;
    private array $rates = [];

    public static function createFromRateCollectionDTO(CurrencyRateCollection $dto, string $dateTimeFormat): self
    {
        $result = new self();
        $result->base = $dto->getCurrencyPair()->getBase();
        $result->quote = $dto->getCurrencyPair()->getQuote();

        foreach ($dto->getRates() as $rate) {
            $result->rates[$rate->getDatetime()->format($dateTimeFormat)] = $rate->getRate();
        }

        return $result;
    }

    public function getBase(): ?string
    {
        return $this->base;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function getRates(): array
    {
        return $this->rates;
    }
}