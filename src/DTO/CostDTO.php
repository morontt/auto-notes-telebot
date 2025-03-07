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

    public function __construct(Cost $data)
    {
        $this->value = $data->getValue();
        $this->currencyCode = $data->getCurrency();
    }

    public function getValue(): float
    {
        return 0.01 * $this->value;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}
