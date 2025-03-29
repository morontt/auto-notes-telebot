<?php

/**
 * User: morontt
 * Date: 12.03.2025
 * Time: 21:54
 */

namespace TeleBot\DTO;

use DateTime;
use Xelbot\Com\Autonotes\UserSettings;

class UserSettingsDTO
{
    private int $id;
    private ?CarDTO $defaultCar = null;
    private ?CurrencyDTO $defaultCurrency = null;
    private ?DateTime $createdAt = null;
    private ?DateTime $updatedAt = null;

    public static function fromData(UserSettings $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();

        if ($data->hasDefaultCar()) {
            $obj->defaultCar = CarDTO::fromData($data->getDefaultCar());
        }
        if ($data->hasDefaultCurrency()) {
            $obj->defaultCurrency = CurrencyDTO::fromData($data->getDefaultCurrency());
        }

        if ($dt = $data->getCreatedAt()) {
            $obj->createdAt = $dt->toDateTime();
        }
        if ($dt = $data->getUpdatedAt()) {
            $obj->updatedAt = $dt->toDateTime();
        }

        return $obj;
    }

    public function reverse(): UserSettings
    {
        $obj = new UserSettings();

        $obj->setId($this->id);
        if ($this->defaultCar) {
            $obj->setDefaultCar($this->defaultCar->reverse());
        }
        if ($this->defaultCurrency) {
            $obj->setDefaultCurrency($this->defaultCurrency->reverse());
        }

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDefaultCar(): ?CarDTO
    {
        return $this->defaultCar;
    }

    public function setDefaultCar(?CarDTO $defaultCar): self
    {
        $this->defaultCar = $defaultCar;

        return $this;
    }

    public function hasDefaultCar(): bool
    {
        return isset($this->defaultCar);
    }

    public function getDefaultCurrency(): ?CurrencyDTO
    {
        return $this->defaultCurrency;
    }

    public function setDefaultCurrency(?CurrencyDTO $defaultCurrency): self
    {
        $this->defaultCurrency = $defaultCurrency;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
}
