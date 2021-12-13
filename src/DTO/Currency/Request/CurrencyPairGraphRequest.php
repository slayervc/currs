<?php
declare(strict_types=1);

namespace App\DTO\Currency\Request;


use Symfony\Component\Validator\Constraints as Assert;

class CurrencyPairGraphRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    public string $from;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    public string $to;

    /**
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    public string $step;

    /**
     * @var CurrencyPairRequest[]
     *
     * @Assert\Valid()
     */
    public array $currencyPairs;
}