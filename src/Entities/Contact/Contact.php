<?php

namespace Aika\Engagebay\Entities\Contact;

use Aika\Engagebay\Utils\Collection;

class Contact
{
    /**
     * Contact ID
     *
     * @var integer $id
     */
    public $id;

    /**
     * Owner ID
     *
     * @var integer $owner_id
     */
    public $owner_id;

    /**
     * Contact Name
     *
     * @var string $name
     */
    public $name;

    /**
     * Contact firstname
     *
     * @var string $firstname
     */
    public $firstname;

    /**
     * Contact lastname
     *
     * @var string $lastname
     */
    public $lastname;

    /**
     * Contact full name
     *
     * @var string $fullname
     */
    public $fullname;

    /**
     * Contact short name
     *
     * @var string $name_sort
     */
    public $name_sort;

    /**
     * Contact email
     *
     * @var string $email
     */
    public $email;

    /**
     * Contact created time
     *
     * @var integer $created_time
     */
    public $created_time;

    /**
     * Contact updated time
     *
     * @var integer $updated_time
     */
    public $updated_time;

    /**
     * Contact status
     *
     * @var string $status
     */
    public $status;

    /**
     * Contact source
     *
     * @var array|ContactSource[] $sources
     */
    public $sources;

    /**
     * Company IDs
     *
     * @var array|integer[]
     */
    public $companyIds = [];

    /**
     * Contact IDs
     *
     * @var array
     */
    public $contactIds = [];

    /**
     * Contact Properties
     *
     * @var Collection<ContactProperty> $properties
     */
    public $properties;

    /**
     * Undocumented variable
     *
     * @var array $listIds
     */
    public $listIds = [];

    /**
     * Contact Owner
     *
     * @var ?ContactOwner $owner
     */
    public $owner;

    /**
     * Contact group name
     *
     * @var string $entiy_group_name
     */
    public $entiy_group_name;

    /**
     * Contact tags
     *
     * @var Collection<ContactTag> $tags
     */
    public $tags;

    public $broadcastIds = [];
    public $openedLinks = [];
    public $emailProperties = [];
    public $unsubscribeList = [];
    public $emailBounceStatus = [];
    public $importedEntity = false;
    public $forceCreate = false;
    public $forceUpdate = false;
    public $score = 5;

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    public function hydrate(array $data): self
    {
        $this->id = intval($data['id'] ?? null);
        $this->owner_id = intval($data['owner_id'] ?? null);
        $this->name = $data['name'] ?? null;
        $this->firstname = $data['firstname'] ?? null;
        $this->lastname = $data['lastname'] ?? null;
        $this->fullname = $data['fullname'] ?? null;
        $this->name_sort = $data['name_sort'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->created_time = intval($data['created_time'] ?? null);
        $this->updated_time = intval($data['created_time'] ?? null);
        $this->status = $data['status'] ?? null;
        $this->companyIds = $data['companyIds'] ?? [];
        $this->contactIds = $data['contactIds'] ?? [];
        $this->listIds = $data['listIds'] ?? [];
        $this->entiy_group_name = $data['entiy_group_name'] ?? null;
        $this->broadcastIds = $data['broadcastIds'] ?? [];
        $this->openedLinks = $data['openedLinks'] ?? [];
        $this->emailProperties = $data['emailProperties'] ?? [];
        $this->unsubscribeList = $data['unsubscribeList'] ?? [];
        $this->emailBounceStatus = $data['emailBounceStatus'] ?? [];
        $this->importedEntity = $data['importedEntity'] ?? false;
        $this->forceCreate = $data['forceCreate'] ?? false;
        $this->forceUpdate = $data['forceUpdate'] ?? false;
        $this->score = intval($data['score'] ?? null);
        
        $this->setProperties($data['properties'] ?? null);
        $this->setOwner($data['owner'] ?? null);
        $this->setTags($data['tags'] ?? null);
        $this->setSources($data['sources'] ?? null);
        if ($this->isNew()) $this->cleanValues();

        return $this;
    }

    public function setProperties(?array $properties): self
    {
        if (empty($properties)) return $this;

        $this->properties = new Collection();
        foreach ($properties as $data) {
            if (!isset($data['name'])) continue;

            $prop = new ContactProperty($data);
            $this->properties[$prop->name] = $prop;
        }

        return $this;
    }

    public function addProperty(ContactProperty $property): self
    {
        if (empty($property->name)) return $this;

        if (!$this->properties) $this->properties = new Collection();

        $this->properties[$property->name] = $property;

        return $this;
    }

    public function getProperty(string $name): ?ContactProperty
    {
        if (!$this->properties) return null;

        $property = $this->properties->get($name, null);

        return $property;
    }

    public function hasProperty(string $name): bool
    {
        if (!$this->properties) return false;

        return isset($this->properties[$name]);
    }

    public function removeProperty(string $name): self
    {
        if (!$this->hasProperty($name)) return $this;

        unset($this->properties[$name]);

        return $this;
    }

    public function setOwner(?array $data): self
    {
        if (empty($data)) return $this;

        $this->owner = new ContactOwner($data);

        return $this;
    }

    public function setTags(?array $data): self
    {
        if (empty($data)) return $this;

        $this->tags = new Collection();
        foreach($data as $dt) {
            $tag = new ContactTag($dt);

            $this->tags[$tag->tag] = $tag;
        }

        return $this;
    }

    public function setSources(?array $data): self
    {
        if (empty($data)) return $this;

        $this->tags = new Collection();
        foreach ($data as $dt) {
            $source = new ContactSource($dt);
            $this->sources[] = $source;
        }

        return $this;
    }

    public function isNew(): bool
    {
        if (is_null($this->id) || $this->id == 0) return true;

        return false;
    }

    private function cleanValues(): self
    {
        if (!is_null($this->name)) $this->name = trim($this->name);
        if (!is_null($this->firstname)) $this->firstname = ucwords(trim($this->firstname));
        if (!is_null($this->lastname)) $this->lastname = ucwords(trim($this->lastname));
        if (!is_null($this->fullname)) $this->fullname = ucwords(trim($this->fullname));
        if (!is_null($this->name_sort)) $this->name_sort = ucwords(trim($this->name_sort));
        if (!is_null($this->email)) $this->email = trim($this->email);

        return $this;
    }
}