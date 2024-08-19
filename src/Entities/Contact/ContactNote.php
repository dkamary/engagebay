<?php

namespace Aika\Engagebay\Entities\Contact;

class ContactNote
{
    public $id;
    public $parentId;
    public $subject;
    public $content;
    public $force = false;
    public $syncIds = [];
    public $owner_id;
    public $type;
    public $created_time;
    public $updated_time;
    public $source;
    public $createFollowUpTask;

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    public function hydrate(array $data = []): self
    {
        $this->id = intval($data['id'] ?? null);
        $this->parentId = intval($data['parentId'] ?? null);
        $this->subject = $data['subject'] ?? null;
        $this->content = $data['content'] ?? null;
        $this->force = boolval($data['force'] ?? null);
        $this->syncIds = $data['syncIds'] ?? [];
        $this->owner_id = intval($data['owner_id'] ?? null);
        $this->type = $data['type'] ?? null;
        $this->created_time = intval($data['created_time'] ?? null);
        $this->updated_time = intval($data['updated_time'] ?? null);
        $this->source = $data['source'] ?? null;
        $this->createFollowUpTask = boolval($data['createFollowUpTask'] ?? null);

        return $this;
    }
}