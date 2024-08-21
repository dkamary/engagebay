<?php

namespace Aika\Engagebay\Utils;

use ArrayAccess;
use Iterator;

class Collection implements ArrayAccess, Iterator
{
    protected $items = [];
    protected $position = 0;
    protected $keys = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
        $this->keys = array_keys($items);
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
        $this->keys = array_keys($this->items); // Update keys when setting a new value
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
        $this->keys = array_keys($this->items); // Update keys when unsetting a value
    }

    // Iterator methods
    public function current(): mixed
    {
        return $this->items[$this->keys[$this->position]];
    }

    public function key(): mixed
    {
        return $this->keys[$this->position];
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
        return isset($this->keys[$this->position]);
    }

    // Custom method
    public function get(string $key, $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }

    public function count(): int
    {
        return count($this->items);
    }
}
