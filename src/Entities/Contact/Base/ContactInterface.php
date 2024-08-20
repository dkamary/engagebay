<?php

namespace Aika\Engagebay\Entities\Contact\Base;

use Aika\Engagebay\Entities\Contact\ContactProperty;
use Aika\Engagebay\Utils\Collection;

interface ContactInterface
{
    public function hydrate(array $data): static;

    public function setProperties(?array $properties): static;

    public function addProperty(ContactProperty $property): static;

    public function setProperty(string $name, string $value, string $fieldType = ContactProperty::FIELD_TYPE_TEXT, string $type = ContactProperty::TYPE_CUSTOM, bool $isSearchable = false, ?string $subType = null): static;

    public function getProperty(string $name): ?ContactProperty;

    public function getPropertyValue(string $name, mixed $default = null): mixed;

    public function hasProperty(string $name): bool;

    public function removeProperty(string $name): static;

    public function setOwner(?array $data): static;

    public function setTags(?array $data): static;

    public function getTags(): Collection;

    public function setSources(?array $data): static;

    public function isNew(): bool;

    public function toArray(): array;

    public function export(): array;
}