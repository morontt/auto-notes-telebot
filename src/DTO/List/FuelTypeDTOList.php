<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 27.09.2025
 * Time: 10:20
 */

namespace TeleBot\DTO\List;

use TeleBot\DTO\FuelTypeDTO;

/**
 * @template-extends BaseList<FuelTypeDTO>
 */
class FuelTypeDTOList extends BaseList
{
    public function supportedClass(): string
    {
        return FuelTypeDTO::class;
    }
}
