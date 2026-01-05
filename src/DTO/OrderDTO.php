<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 27.09.2025
 * Time: 12:57
 */

namespace TeleBot\DTO;

use AutoNotes\Server\Order;
use DateTime;

class OrderDTO extends BaseDTO
{
    protected int $id = 0;
    protected string $description = '';
    protected ?string $capacity = null;
    protected ?int $distance = null;
    protected ?CarDTO $car = null;
    protected ?CostDTO $cost = null;
    protected ?OrderTypeDTO $type = null;
    protected ?DateTime $date = null;
    protected ?DateTime $usedAt = null;
    protected ?DateTime $createdAt = null;

    public static function fromData(Order $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();
        $obj->distance = $data->getDistance();
        $obj->description = $data->getDescription();
        $obj->capacity = $data->getCapacity();

        if ($data->hasCar()) {
            $obj->car = CarDTO::fromData($data->getCar());
        }
        if ($data->hasCost()) {
            $obj->cost = CostDTO::fromData($data->getCost());
        }
        if ($data->hasType()) {
            $obj->type = OrderTypeDTO::fromData($data->getType());
        }

        if ($dt = $data->getDate()) {
            $obj->date = self::fromPbTimestamp($dt);
        }

        if ($dt = $data->getUsedAt()) {
            $obj->usedAt = self::fromPbTimestamp($dt);
        }

        if ($dt = $data->getCreatedAt()) {
            $obj->createdAt = $dt->toDateTime();
        }

        return $obj;
    }

    public function reverse(): Order
    {
        $obj = new Order();

        $obj->setId($this->id);
        $obj->setDescription($this->description);

        if ($this->capacity) {
            $obj->setCapacity($this->capacity);
        }
        if ($this->car) {
            $obj->setCar($this->car->reverse());
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
        if ($this->usedAt) {
            $obj->setUsedAt(self::toPbTimestamp($this->usedAt));
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

    public function getCapacity(): ?string
    {
        return $this->capacity;
    }

    public function setCapacity(?string $capacity): self
    {
        $this->capacity = $capacity;

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

    public function getType(): ?OrderTypeDTO
    {
        return $this->type;
    }

    public function setType(?OrderTypeDTO $type): self
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

    public function getUsedAt(): ?DateTime
    {
        return $this->usedAt;
    }

    public function setUsedAt(?DateTime $usedAt): self
    {
        $this->usedAt = $usedAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
}
