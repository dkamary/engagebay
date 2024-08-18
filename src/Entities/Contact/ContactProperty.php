<?php

namespace Aika\Engagebay\Entities\Contact;

class ContactProperty
{
    // Field type
    const FIELD_TYPE_LIST = 'LIST';
    const FIELD_TYPE_TEXT = 'TEXT';
    const FIELD_TYPE_NUMBER = 'NUMBER';
    const FIELD_TYPE_DATE = 'DATE';
    const FIELD_TYPE_CHECKBOX = 'CHECKBOX';
    const FIELD_TYPE_TEXTAREA = 'TEXTAREA';
    const FIELD_TYPE_PHONE = 'PHONE';
    const FIELD_TYPE_URL = 'URL';
    const FIELD_TYPE_MULTICHECKBOX = 'MULTICHECKBOX';

    // Type
    const TYPE_CUSTOM = 'CUSTOM';
    const TYPE_SYSTEM = 'SYSTEM';

    // Subtype
    const SUBTYPE_PRIMARY = 'primary';
    const SUBTYPE_WORK = 'work';

    public $name;
    public $value;
    public $field_type;
    /**
     * @var bool $is_searchable
     */
    public $is_searchable;
    public $type;
    public $subtype;

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    public function hydrate(array $data): self
    {
        $this->name = $data['name'] ?? null;
        $this->value = $data['value'] ?? null;
        $this->field_type = $data['field_type'] ?? self::FIELD_TYPE_TEXT;
        $this->is_searchable = boolval($data['is_searchable'] ?? null);
        $this->type = $data['type'] ?? self::TYPE_CUSTOM;
        $this->subtype = $data['subtype'] ?? null;

        return $this;
    }

    public function toArray(): array
    {
        $array = [
            'name' => !is_null($this->name) ? trim($this->name) : '',
            'value' => (string)$this->value,
            'field_type' => $this->field_type,
            'type' => $this->type,
            'is_searchable' => $this->is_searchable,
        ];

        if ($this->subtype) $array['subtype'] = $this->subtype;

        return $array;
    }
}