<?php
declare(strict_types=1);

namespace App\DTO\Currency\Request;


class CurrencyPairRequest
{
    public string $base;
    public string $quote;
}