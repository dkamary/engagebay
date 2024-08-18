<?php

namespace Aika\Engagebay\Entities\Contact;

class ContactSource
{
    /**
     * @var string $type
     */
    public $type;

    /**
     * @var integer $subscribed_on
     */
	public $subscribed_on;
	
    /**
     * @var string $status
     */
    public $status;

    /**
     * @var integer $custom
     */
	public $custom;

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    public function hydrate(array $data): self
    {
        $this->type = $data['type'] ?? null;
        $this->subscribed_on = intval($data['subscribed_on'] ?? null);
        $this->status = $data['status'] ?? null;
        $this->custom = $data['custom'] ?? null;

        return $this;
    }
}