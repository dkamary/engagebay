<?php

namespace Aika\Engagebay\Transactions;

use Aika\Engagebay\Utils\Collection;

class ListResult extends Result
{
    const FOUND = 100;

    public function __construct(int $status = self::UNKNOW, ?string $message = null, ?Collection $collection = null)
    {
        parent::__construct($status, $message, $collection);
    }

    public function getStatusText(): string
    {
        if (self::FOUND == $this->status) return 'found';
        return parent::getStatusText();
    }

    public function isSuccess(): bool
    {
        return (self::FOUND == $this->status) ? true : parent::isSuccess();
    }

    public function getCollection(): ?Collection
    {
        return $this->data;
    }

    public function setCollection(Collection $collection): self
    {
        $this->data = $collection;

        return $this;
    }
}