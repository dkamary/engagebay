<?php

namespace Aika\Engagebay\Utils;

use ArrayAccess;
use Iterator;

class Collection implements ArrayAccess, Iterator
{
    protected array $items = [];
    protected int $position = 0;

    public function __construct(array $items = [])
    {
        $this->items = $items;
        $this->position = 0;
    }

    // ArrayAccess methods
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    // Iterator methods
    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    // Custom method
    public function get(string $key, $default = null)
    {
        return $this->items[$key] ?? $default;
    }

    public function count(): int
    {
        return count($this->items);
    }
}
