<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 08.10.2025
 * Time: 10:12
 */

namespace TeleBot\DTO\List;

use TeleBot\DTO\OrderTypeDTO;

/**
 * @extends BaseList<OrderTypeDTO>
 */
class OrderTypeDTOList extends BaseList
{
    public function supportedClass(): string
    {
        return OrderTypeDTO::class;
    }
}
