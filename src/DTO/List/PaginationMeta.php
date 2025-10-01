<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 23.09.2025
 * Time: 23:24
 */

namespace TeleBot\DTO\List;

readonly class PaginationMeta
{
    public function __construct(
        private int $current,
        private int $last
    ) {
    }

    public function getLast(): int
    {
        return $this->last;
    }

    public function getCurrent(): int
    {
        return $this->current;
    }
}
