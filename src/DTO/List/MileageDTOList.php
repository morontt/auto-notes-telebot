<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 14.12.2025
 */

namespace TeleBot\DTO\List;

use TeleBot\DTO\MileageDTO;

/**
 * @extends BaseList<MileageDTO>
 */
class MileageDTOList extends BaseList
{
    public function supportedClass(): string
    {
        return MileageDTO::class;
    }
}
