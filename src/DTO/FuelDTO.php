<?php

/**
 * User: morontt
 * Date: 06.03.2025
 * Time: 09:29
 */

namespace TeleBot\DTO;

use DateTime;
use Xelbot\Com\Autonotes\Fuel;

class FuelDTO
{
    private int $id;
    private int $value;
    private int $distance;
    private ?CarDTO $car = null;
    private ?CostDTO $cost = null;
    private ?FillingStationDTO $station = null;
    private ?DateTime $date = null;
    private ?DateTime $createdAt = null;

    public static function fromData(Fuel $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();
        $obj->value = $data->getValue();
        $obj->distance = $data->getDistance();

        if ($data->hasCar()) {
            $obj->car = CarDTO::fromData($data->getCar());
        }
        if ($data->hasCost()) {
            $obj->cost = CostDTO::fromData($data->getCost());
        }
        if ($data->hasStation()) {
            $obj->station = FillingStationDTO::fromData($data->getStation());
        }

        if ($dt = $data->getDate()) {
            $obj->date = $dt->toDateTime();
        }

        if ($dt = $data->getCreatedAt()) {
            $obj->createdAt = $dt->toDateTime();
        }

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): float
    {
        return 0.01 * $this->value;
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function getCar(): ?CarDTO
    {
        return $this->car;
    }

    public function setCar(?CarDTO $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getCost(): ?CostDTO
    {
        return $this->cost;
    }

    public function getStation(): ?FillingStationDTO
    {
        return $this->station;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
}
