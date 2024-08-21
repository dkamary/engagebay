<?php

namespace Aika\Engagebay\Entities\Form;

class Form
{
    public $id;
    public $name;
    public $alias_name;
    public $owner_id;
    public $created_time;
    public $updated_time;
    public $formHtml;
    public $form_attributes;
    public $enable_whitelabel;
    public $form_style;
    public $version;
    public $incentiveEmail;
    public $thumbnail;
    public $formStats;

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    public function hydrate(array $data): self
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->alias_name = $data['alias_name'] ?? null;
        $this->owner_id = $data['owner_id'] ?? null;
        $this->created_time = $data['created_time'] ?? null;
        $this->updated_time = $data['updated_time'] ?? null;
        $this->formHtml = $data['formHtml'] ?? null;
        $this->form_attributes = $data['form_attributes'] ?? null;
        $this->enable_whitelabel = $data['enable_whitelabel'] ?? null;
        $this->form_style = $data['form_style'] ?? null;
        $this->version = $data['version'] ?? null;
        $this->incentiveEmail = $data['incentiveEmail'] ?? null;
        $this->thumbnail = $data['thumbnail'] ?? null;
        $this->formStats = $data['formStats'] ?? null;

    return $this;
    }
}