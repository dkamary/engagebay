<?php

namespace Aika\Engagebay\Form;

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

    public function hydrate(array $dat): static
    {
        //
    }
}