<?php

/**
 * User: morontt
 * Date: 07.03.2025
 * Time: 10:10
 */

namespace TeleBot\DTO;

use Xelbot\Com\Autonotes\Cost;

class CostDTO
{
    private int $value;
    private string $currencyCode;

    public static function fromData(Cost $data): self
    {
        $obj = new self();

        $obj->value = $data->getValue();
        $obj->currencyCode = $data->getCurrency();

        return $obj;
    }

    public function reverse(): Cost
    {
        $obj = new Cost();

        $obj->setValue($this->value);
        $obj->setCurrency($this->currencyCode);

        return $obj;
    }

    public function getValue(): float
    {
        return 0.01 * $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = (int)(0.5 + 100 * $value);

        return $this;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }
}
