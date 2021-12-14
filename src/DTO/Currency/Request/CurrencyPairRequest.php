<?php
declare(strict_types=1);

namespace App\DTO\Currency\Request;


use Symfony\Component\Validator\Constraints as Assert;

class CurrencyPairRequest
{
    /**
     * @Assert\Regex("/^[A-Z]{3}$/")
     */
    private string $base;

    /**
     * @Assert\Regex("/^[A-Z]{3}$/")
     */
    private string $quote;

    public function getBase(): string
    {
        return $this->base;
    }

    public function setBase(string $base): CurrencyPairRequest
    {
        $this->base = $base;

        return $this;
    }

    public function getQuote(): string
    {
        return $this->quote;
    }

    public function setQuote(string $quote): CurrencyPairRequest
    {
        $this->quote = $quote;

        return $this;
    }
}