<?php

/**
 * User: morontt
 * Date: 02.03.2025
 * Time: 22:16
 */

namespace TeleBot\DTO;

use DateTime;
use Xelbot\Com\Autonotes\Car;

class CarDTO
{
    private int $id;
    private string $name;
    private ?string $vin;
    private ?int $year;
    private bool $default;
    private DateTime $createdAt;

    public function __construct(Car $data)
    {
        $this->id = $data->getId();
        $this->name = $data->getName();
        $this->vin = $data->getVin() ?: null;
        $this->year = $data->getYear() ?: null;
        $this->default = $data->getDefault();

        $dt = $data->getCreatedAt();
        if ($dt) {
            $this->createdAt = $dt->toDateTime();
        } else {
            $this->createdAt = DateTime::createFromFormat('U', 0);
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
