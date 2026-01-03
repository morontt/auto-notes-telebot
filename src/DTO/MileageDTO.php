<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 14.12.2025
 */

namespace TeleBot\DTO;

use AutoNotes\Server\Mileage;
use DateTime;

class MileageDTO extends BaseDTO
{
    protected int $id = 0;
    protected int $distance = 0;
    protected ?CarDTO $car = null;
    protected ?DateTime $date = null;
    protected ?DateTime $createdAt = null;

    public static function fromData(Mileage $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();
        $obj->distance = $data->getDistance();

        if ($data->hasCar()) {
            $obj->car = CarDTO::fromData($data->getCar());
        }

        if ($dt = $data->getDate()) {
            $obj->date = self::fromPbTimestamp($dt);
        }

        if ($dt = $data->getCreatedAt()) {
            $obj->createdAt = $dt->toDateTime();
        }

        return $obj;
    }

    public function reverse(): Mileage
    {
        $obj = new Mileage();

        $obj->setId($this->id);
        $obj->setDistance($this->distance);

        if ($this->car) {
            $obj->setCar($this->car->reverse());
        }
        if ($this->date) {
            $obj->setDate(self::toPbTimestamp($this->date));
        }

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): self
    {
        $this->distance = $distance;

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
