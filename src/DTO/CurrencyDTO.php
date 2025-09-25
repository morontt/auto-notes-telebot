<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 11.03.2025
 * Time: 00:21
 */

namespace TeleBot\DTO;

use AutoNotes\Server\Currency;
use DateTime;

class CurrencyDTO
{
    private int $id;
    private string $name;
    private string $code;
    private bool $default;
    private ?DateTime $createdAt = null;

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->name, $this->code);
    }

    public static function fromData(Currency $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();
        $obj->name = $data->getName();
        $obj->code = $data->getCode();
        $obj->default = $data->getDefault();

        if ($dt = $data->getCreatedAt()) {
            $obj->createdAt = $dt->toDateTime();
        }

        return $obj;
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
