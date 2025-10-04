<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 27.09.2025
 * Time: 12:19
 */

namespace TeleBot\DTO\List;

use TeleBot\DTO\CurrencyDTO;

/**
 * @extends BaseList<CurrencyDTO>
 */
class CurrencyDTOList extends BaseList
{
    public function supportedClass(): string
    {
        return CurrencyDTO::class;
    }
}
