<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 27.09.2025
 * Time: 09:43
 */

namespace TeleBot\DTO\List;

use TeleBot\DTO\FillingStationDTO;

/**
 * @extends BaseList<FillingStationDTO>
 */
class FillingStationDTOList extends BaseList
{
    public function supportedClass(): string
    {
        return FillingStationDTO::class;
    }
}
