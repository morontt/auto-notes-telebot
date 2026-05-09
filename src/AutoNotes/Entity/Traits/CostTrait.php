<?php declare(strict_types=1);

namespace TeleBot\AutoNotes\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use TeleBot\AutoNotes\Entity\Currency;

trait CostTrait
{
    #[ORM\Column(type: 'decimal', precision: 8, scale: 2)]
    private string $cost;

    /**
     * @var Currency
     */
    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    private Currency $currency;

    public function getCost(): float
    {
        return (float)$this->cost;
    }

    public function setCost(float $cost): self
    {
        $this->cost = (string)$cost;

        return $this;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
