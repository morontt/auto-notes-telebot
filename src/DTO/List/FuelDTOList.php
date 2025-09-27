<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 23.09.2025
 * Time: 22:02
 */

namespace TeleBot\DTO\List;

use TeleBot\DTO\FuelDTO;

/**
 * @template-extends BaseList<FuelDTO>
 */
class FuelDTOList extends BaseList
{
    public function supportedClass(): string
    {
        return FuelDTO::class;
    }
}
