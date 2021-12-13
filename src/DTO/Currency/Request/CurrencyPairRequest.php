<?php
declare(strict_types=1);

namespace App\DTO\Currency\Request;


use Symfony\Component\Validator\Constraints as Assert;

class CurrencyPairRequest
{
    /**
     * @Assert\Regex("/^[A-Z]{3}$/")
     */
    public string $base;

    /**
     * @Assert\Regex("/^[A-Z]{3}$/")
     */
    public string $quote;
}