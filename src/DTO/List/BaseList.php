<?php

/**
 * User: morontt
 * Date: 23.09.2025
 * Time: 22:02
 */

namespace TeleBot\DTO\List;

use Countable;
use Iterator;

/**
 * @template T
 * @template-implements Iterator<int, T>
 */
class BaseList implements Iterator, Countable
{
    /**
     * @var T[]
     */
    private array $container;

    private int $position;
    private PaginationMeta $meta;

    public function __construct(int $current, int $last)
    {
        $this->container = [];
        $this->position = 0;

        $this->meta = new PaginationMeta($current, $last);
    }

    public function getPaginationMeta(): PaginationMeta
    {
        return $this->meta;
    }

    public function count(): int
    {
        return count($this->container);
    }

    /**
     * @phpstan-return T
     */
    public function current(): mixed
    {
        return $this->container[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->container[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @param T $item
     */
    protected function addItem(mixed $item): void
    {
        $this->container[] = $item;
    }
}
