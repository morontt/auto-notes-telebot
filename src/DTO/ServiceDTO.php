<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 05.01.2026
 */

namespace TeleBot\DTO;

use AutoNotes\Server\Service;
use DateTime;

class ServiceDTO extends BaseDTO
{
    protected int $id = 0;
    protected string $description = '';
    protected ?int $distance = null;
    protected ?CarDTO $car = null;
    protected ?CostDTO $cost = null;
    protected ?DateTime $date = null;
    protected ?DateTime $createdAt = null;

    public static function fromData(Service $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();
        $obj->distance = $data->getDistance();
        $obj->description = $data->getDescription();

        if ($data->hasCar()) {
            $obj->car = CarDTO::fromData($data->getCar());
        }
        if ($data->hasCost()) {
            $obj->cost = CostDTO::fromData($data->getCost());
        }

        if ($dt = $data->getDate()) {
            $obj->date = self::fromPbTimestamp($dt);
        }

        if ($dt = $data->getCreatedAt()) {
            $obj->createdAt = $dt->toDateTime();
        }

        return $obj;
    }

    public function reverse(): Service
    {
        $obj = new Service();

        $obj->setId($this->id);
        $obj->setDescription($this->description);

        if ($this->car) {
            $obj->setCar($this->car->reverse());
        }
        if ($this->cost) {
            $obj->setCost($this->cost->reverse());
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(?int $distance): self
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
