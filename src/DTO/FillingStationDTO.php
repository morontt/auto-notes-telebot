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

    public function __construct(FillingStation $data)
    {
        $this->id = $data->getId();
        $this->name = $data->getName();
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
