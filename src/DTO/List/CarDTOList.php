<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 27.09.2025
 * Time: 12:15
 */

namespace TeleBot\DTO\List;

use TeleBot\DTO\CarDTO;

/**
 * @extends BaseList<CarDTO>
 */
class CarDTOList extends BaseList
{
    public function supportedClass(): string
    {
        return CarDTO::class;
    }
}
