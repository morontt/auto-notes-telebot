<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 30.09.2025
 * Time: 07:42
 */

namespace TeleBot\DTO;

use AutoNotes\Server\OrderType;

class OrderTypeDTO extends BaseDTO
{
    protected int $id;
    protected string $name;

    public function __toString(): string
    {
        return $this->name;
    }

    public static function fromData(OrderType $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();
        $obj->name = $data->getName();

        return $obj;
    }

    public function reverse(): OrderType
    {
        $obj = new OrderType();
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
