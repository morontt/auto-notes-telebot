<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 07.09.2025
 * Time: 23:20
 */

namespace TeleBot\DTO;

use AutoNotes\Server\FuelType;

class FuelTypeDTO extends BaseDTO
{
    private int $id;
    private string $name;

    public function __toString(): string
    {
        return $this->name;
    }

    public static function fromData(FuelType $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();
        $obj->name = $data->getName();

        return $obj;
    }

    public function reverse(): FuelType
    {
        $obj = new FuelType();
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
}
