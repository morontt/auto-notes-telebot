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
    private ?CarDTO $car;
    private ?CostDTO $cost;
    private ?FillingStationDTO $station;
    private ?DateTime $date;
    private ?DateTime $createdAt;

    public function __construct(Fuel $data)
    {
        $this->id = $data->getId();
        $this->value = $data->getValue();
        $this->distance = $data->getDistance();

        if ($data->hasCar()) {
            $this->car = new CarDTO($data->getCar());
        }
        if ($data->hasCost()) {
            $this->cost = new CostDTO($data->getCost());
        }
        if ($data->hasStation()) {
            $this->station = new FillingStationDTO($data->getStation());
        }

        if ($dt = $data->getDate()) {
            $this->date = $dt->toDateTime();
        }

        if ($dt = $data->getCreatedAt()) {
            $this->createdAt = $dt->toDateTime();
        }
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
