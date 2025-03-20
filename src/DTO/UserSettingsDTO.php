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

    public function __construct(UserSettings $data)
    {
        $this->id = $data->getId();

        if ($data->hasDefaultCar()) {
            $this->defaultCar = new CarDTO($data->getDefaultCar());
        }
        if ($data->hasDefaultCurrency()) {
            $this->defaultCurrency = new CurrencyDTO($data->getDefaultCurrency());
        }

        if ($dt = $data->getCreatedAt()) {
            $this->createdAt = $dt->toDateTime();
        }

        if ($dt = $data->getUpdatedAt()) {
            $this->updatedAt = $dt->toDateTime();
        }
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
