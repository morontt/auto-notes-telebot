<?php

/**
 * User: morontt
 * Date: 07.03.2025
 * Time: 10:17
 */

namespace TeleBot\DTO;

use AutoNotes\Server\FillingStation;

class FillingStationDTO
{
    private int $id;
    private string $name;

    public function __toString(): string
    {
        return $this->name;
    }

    public static function fromData(FillingStation $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();
        $obj->name = $data->getName();

        return $obj;
    }

    public function reverse(): FillingStation
    {
        $obj = new FillingStation();
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
