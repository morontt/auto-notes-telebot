<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 05.01.2026
 */

namespace TeleBot\DTO\List;

use TeleBot\DTO\ServiceDTO;

/**
 * @extends BaseList<ServiceDTO>
 */
class ServiceDTOList extends BaseList
{
    public function supportedClass(): string
    {
        return ServiceDTO::class;
    }
}
