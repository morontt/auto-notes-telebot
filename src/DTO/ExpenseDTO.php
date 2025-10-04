<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 03.10.2025
 * Time: 22:45
 */

namespace TeleBot\DTO;

use AutoNotes\Server\Expense;
use DateTime;

class ExpenseDTO
{
    protected int $id = 0;
    protected string $description = '';
    protected int $type = 0;
    protected ?CarDTO $car = null;
    protected ?CostDTO $cost = null;
    protected ?DateTime $date = null;
    protected ?DateTime $createdAt = null;

    public static function fromData(Expense $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();
        $obj->description = $data->getDescription();
        $obj->type = $data->getType();

        if ($data->hasCar()) {
            $obj->car = CarDTO::fromData($data->getCar());
        }
        if ($data->hasCost()) {
            $obj->cost = CostDTO::fromData($data->getCost());
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

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
