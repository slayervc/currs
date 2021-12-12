<?php
declare(strict_types=1);

namespace App\DTO\Currency\Request;


class CurrencyPairGraphRequest
{
    public string $from;
    public string $to;
    public string $step;

    /** @var CurrencyPairRequest[] */
    public array $currencyPairs;
}