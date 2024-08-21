<?php

namespace Aika\Engagebay\Entities\Contact\Base;

use Aika\Engagebay\Entities\Contact\ContactProperty;
use Aika\Engagebay\Utils\Collection;

interface ContactInterface
{
    public function hydrate(array $data): ContactInterface;

    public function setProperties(?array $properties): ContactInterface;

    public function addProperty(ContactProperty $property): ContactInterface;

    public function setProperty(string $name, string $value, string $fieldType = ContactProperty::FIELD_TYPE_TEXT, string $type = ContactProperty::TYPE_CUSTOM, bool $isSearchable = false, ?string $subType = null): ContactInterface;

    public function getProperty(string $name): ?ContactProperty;

    public function getPropertyValue(string $name, $default = null);

    public function hasProperty(string $name): bool;

    public function removeProperty(string $name): ContactInterface;

    public function setOwner(?array $data): ContactInterface;

    public function setTags(?array $data): ContactInterface;

    public function getTags(): Collection;

    public function addTag(string $tag): ContactInterface;

    public function setSources(?array $data): ContactInterface;

    public function isNew(): bool;

    public function toArray(): array;

    public function export(): array;
}