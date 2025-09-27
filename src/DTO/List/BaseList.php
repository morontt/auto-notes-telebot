<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 23.09.2025
 * Time: 22:02
 */

namespace TeleBot\DTO\List;

use Countable;
use InvalidArgumentException;
use Iterator;

/**
 * @template T of object
 * @template-implements Iterator<int, T>
 */
abstract class BaseList implements Iterator, Countable
{
    /**
     * @var T[]
     */
    private array $container;

    private int $position;
    private PaginationMeta $meta;

    public function __construct(int $current = 1, int $last = 1)
    {
        $this->container = [];
        $this->position = 0;

        $this->meta = new PaginationMeta($current, $last);
    }

    abstract public function supportedClass(): string;

    /**
     * @phpstan-param T $item
     */
    public function add(object $item): void
    {
        if (get_class($item) !== $this->supportedClass()) {
            throw new InvalidArgumentException(
                sprintf('Invalid list class: %s, expected %s', get_class($item), $this->supportedClass())
            );
        }

        $this->addItem($item);
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
    protected function addItem(object $item): void
    {
        $this->container[] = $item;
    }
}
