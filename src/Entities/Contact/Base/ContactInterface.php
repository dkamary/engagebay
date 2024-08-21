<?php

namespace Aika\Engagebay\Entities\Contact\Base;

use Aika\Engagebay\Entities\Contact\ContactProperty;
use Aika\Engagebay\Utils\Collection;

interface ContactInterface
{
    public function hydrate(array $data): self;

    public function setProperties(?array $properties): self;

    public function addProperty(ContactProperty $property): self;

    public function setProperty(string $name, string $value, string $fieldType = ContactProperty::FIELD_TYPE_TEXT, string $type = ContactProperty::TYPE_CUSTOM, bool $isSearchable = false, ?string $subType = null): self;

    public function getProperty(string $name): ?ContactProperty;

    public function getPropertyValue(string $name, $default = null);

    public function hasProperty(string $name): bool;

    public function removeProperty(string $name): self;

    public function setOwner(?array $data): self;

    public function setTags(?array $data): self;

    public function getTags(): Collection;

    public function addTag(string $tag): self;

    public function setSources(?array $data): self;

    public function isNew(): bool;

    public function toArray(): array;

    public function export(): array;
}