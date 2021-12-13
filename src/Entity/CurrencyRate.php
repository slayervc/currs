<?php
declare(strict_types=1);

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="currency_rate", indexes={@ORM\Index(name="currency_pair_idx", columns={"base", "quote"})})
 */
class CurrencyRate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=3, options={"fixed" = true})
     */
    private ?string $base = null;

    /**
     * @ORM\Column(type="string", length=3, options={"fixed" = true})
     */
    private ?string $quote = null;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private ?\DateTimeInterface $datetime = null;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=4)
     */
    private ?float $rate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): CurrencyRate
    {
        $this->id = $id;

        return $this;
    }

    public function getBase(): ?string
    {
        return $this->base;
    }

    public function setBase(?string $base): CurrencyRate
    {
        $this->base = $base;

        return $this;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(?string $quote): CurrencyRate
    {
        $this->quote = $quote;

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(?\DateTimeInterface $datetime): CurrencyRate
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): CurrencyRate
    {
        $this->rate = $rate;

        return $this;
    }
}