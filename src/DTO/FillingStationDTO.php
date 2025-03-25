<?php

/**
 * User: morontt
 * Date: 07.03.2025
 * Time: 10:17
 */

namespace TeleBot\DTO;

use Xelbot\Com\Autonotes\FillingStation;

class FillingStationDTO
{
    private int $id;
    private string $name;

    public static function fromData(FillingStation $data): self
    {
        $obj = new self();

        $obj->id = $data->getId();
        $obj->name = $data->getName();

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
