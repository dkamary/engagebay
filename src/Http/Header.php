<?php

namespace Aika\Engagebay\Http;

class Header
{
    protected $name;
    protected $value;

    public function __construct(?string $name = null, mixed $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function toArray(): array
    {
        if (!$this->name) return [$this->name];
        
        return [
            $this->name => $this->value,
        ];
    }
}