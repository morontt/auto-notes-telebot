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
    private ?CarDTO $defaultCar;
    private ?CurrencyDTO $defaultCurrency;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;

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

    public function getId(): int
    {
        return $this->id;
    }

    public function getDefaultCar(): ?CarDTO
    {
        return $this->defaultCar;
    }

    public function getDefaultCurrency(): ?CurrencyDTO
    {
        return $this->defaultCurrency;
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
