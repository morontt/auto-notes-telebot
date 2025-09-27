<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 02.03.2025
 * Time: 22:16
 */

namespace TeleBot\DTO;

use AutoNotes\Server\Car;
use DateTime;

class CarDTO extends BaseDTO
{
    protected int $id;
    protected string $name;
    protected ?string $vin;
    protected ?int $year;
    protected bool $default;
    protected ?DateTime $createdAt = null;

    public function __toString(): string
    {
        return $this->name;
    }

    public static function fromData(Car $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();
        $obj->name = $data->getName();
        $obj->vin = $data->getVin() ?: null;
        $obj->year = $data->getYear() ?: null;
        $obj->default = $data->getDefault();

        if ($dt = $data->getCreatedAt()) {
            $obj->createdAt = $dt->toDateTime();
        }

        return $obj;
    }

    public function reverse(): Car
    {
        $obj = new Car();
        $obj->setId($this->id);

        return $obj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
}
