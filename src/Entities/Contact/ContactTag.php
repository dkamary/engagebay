<?php

namespace Aika\Engagebay\Entities\Contact;

class ContactTag
{
    /**
     *  @var string $tag
     */
    public $tag;

    /**
     *  @var int $created_time
     */
	public $created_time;

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    public function hydrate(array $data): self
    {
        $this->tag = $data['tag'] ?? null;
        $this->created_time = intval($data['created_time'] ?? null);

        return $this;
    }
}