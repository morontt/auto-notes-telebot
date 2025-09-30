<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 30.09.2025
 * Time: 07:37
 */

namespace TeleBot\DTO\List;

use TeleBot\DTO\OrderDTO;

/**
 * @template-extends BaseList<OrderDTO>
 */
class OrderDTOList extends BaseList
{
    public function supportedClass(): string
    {
        return OrderDTO::class;
    }
}
