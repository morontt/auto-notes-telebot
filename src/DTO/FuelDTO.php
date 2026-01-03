<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 06.03.2025
 * Time: 09:29
 */

namespace TeleBot\DTO;

use AutoNotes\Server\Fuel;
use DateTime;

class FuelDTO extends BaseDTO
{
    protected int $id = 0;
    protected int $value = 0;
    protected ?int $distance = null;
    protected ?CarDTO $car = null;
    protected ?CostDTO $cost = null;
    protected ?FillingStationDTO $station = null;
    protected ?FuelTypeDTO $type = null;
    protected ?DateTime $date = null;
    protected ?DateTime $createdAt = null;

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
        if ($data->hasType()) {
            $obj->type = FuelTypeDTO::fromData($data->getType());
        }

        if ($dt = $data->getDate()) {
            $obj->date = self::fromPbTimestamp($dt);
        }

        if ($dt = $data->getCreatedAt()) {
            $obj->createdAt = $dt->toDateTime();
        }

        return $obj;
    }

    public function reverse(): Fuel
    {
        $obj = new Fuel();

        $obj->setId($this->id);
        $obj->setValue($this->value);

        if ($this->car) {
            $obj->setCar($this->car->reverse());
        }
        if ($this->station) {
            $obj->setStation($this->station->reverse());
        }
        if ($this->cost) {
            $obj->setCost($this->cost->reverse());
        }
        if ($this->type) {
            $obj->setType($this->type->reverse());
        }
        if ($this->date) {
            $obj->setDate(self::toPbTimestamp($this->date));
        }
        if ($this->distance) {
            $obj->setDistance($this->distance);
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

    public function setValue(float $value): self
    {
        $this->value = (int)(0.5 + 100 * $value);

        return $this;
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): self
    {
        $this->distance = $distance;

        return $this;
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

    public function setCost(?CostDTO $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getStation(): ?FillingStationDTO
    {
        return $this->station;
    }

    public function setStation(?FillingStationDTO $station): self
    {
        $this->station = $station;

        return $this;
    }

    public function getType(): ?FuelTypeDTO
    {
        return $this->type;
    }

    public function setType(?FuelTypeDTO $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
}
