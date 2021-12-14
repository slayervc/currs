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
    private ?string $from =null;

    /**
     * @Assert\DateTime()
     */
    private ?string $to = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    private ?string $step = null;

    /**
     * @var CurrencyPairRequest[]
     *
     * @Assert\Valid()
     */
    private array $currencyPairs = [];

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(?string $from): CurrencyPairGraphRequest
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function setTo(?string $to): CurrencyPairGraphRequest
    {
        $this->to = $to;

        return $this;
    }

    public function getStep(): ?string
    {
        return $this->step;
    }

    public function setStep(?string $step): CurrencyPairGraphRequest
    {
        $this->step = $step;

        return $this;
    }

    public function getCurrencyPairs(): array
    {
        return $this->currencyPairs;
    }

    public function setCurrencyPairs(array $currencyPairs): CurrencyPairGraphRequest
    {
        $this->currencyPairs = $currencyPairs;

        return $this;
    }
}