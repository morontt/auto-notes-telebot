<?php

/**
 * User: morontt
 * Date: 11.03.2025
 * Time: 00:21
 */

namespace TeleBot\DTO;

use DateTime;
use Xelbot\Com\Autonotes\Currency;

class CurrencyDTO
{
    private int $id;
    private string $name;
    private string $code;
    private bool $default;
    private ?DateTime $createdAt;

    public function __construct(Currency $data)
    {
        $this->id = $data->getId();
        $this->name = $data->getName();
        $this->code = $data->getCode();
        $this->default = $data->getDefault();

        if ($dt = $data->getCreatedAt()) {
            $this->createdAt = $dt->toDateTime();
        }
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->name, $this->code);
    }

    public function reverse(): Currency
    {
        $obj = new Currency();
        $obj->setId($this->id);

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
}
