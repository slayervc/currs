<?php
declare(strict_types=1);

namespace App\DTO\Currency;


class CurrencyPair
{
    private string $base;
    private string $quote;

    public function __construct(string $base, string $quote)
    {
        $this->base = $base;
        $this->quote = $quote;
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function getQuote(): string
    {
        return $this->quote;
    }
}