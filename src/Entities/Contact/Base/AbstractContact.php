<?php

namespace Aika\Engagebay\Entities\Contact\Base;

use Aika\Engagebay\Entities\Contact\ContactOwner;
use Aika\Engagebay\Entities\Contact\ContactProperty;
use Aika\Engagebay\Entities\Contact\ContactSource;
use Aika\Engagebay\Entities\Contact\ContactTag;
use Aika\Engagebay\Managers\ContactManager;
use Aika\Engagebay\Transactions\ContactSubmitResult;
use Aika\Engagebay\Transactions\Result;
use Aika\Engagebay\Utils\Collection;

abstract class AbstractContact implements ContactInterface
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
    
    public function hydrate(array $data): ContactInterface
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

        $this->setProperties($properties ?? null);
        $this->setOwner($data['owner'] ?? null);
        $this->setTags($data['tags'] ?? null);
        $this->setSources($data['sources'] ?? null);
        if ($this->isNew()) $this->cleanValues();

        return $this;
    }

    public function setProperties(?array $properties): ContactInterface
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

    public function addProperty(ContactProperty $property): ContactInterface
    {
        if (empty($property->name)) return $this;

        if (!$this->properties) $this->properties = new Collection();

        $this->properties[$property->name] = $property;

        return $this;
    }

    public function setProperty(string $name, string $value, string $fieldType = ContactProperty::FIELD_TYPE_TEXT, string $type = ContactProperty::TYPE_CUSTOM, bool $isSearchable = false, ?string $subType = null): ContactInterface
    {
        if (!$this->hasProperty($name)) {

            return $this->addProperty(new ContactProperty([
                'name' => $name,
                'value' => $value,
                'field_type' => $fieldType,
                'type' => $type,
                'is_searchable' => $isSearchable,
                'subtype' => $subType,
            ]));
        }

        $this->properties[$name]->value = $value;

        return $this;
    }

    public function getProperty(string $name): ?ContactProperty
    {
        if (!$this->properties) return null;

        $property = $this->properties->get($name, null);

        return $property;
    }

    public function getPropertyValue(string $name, $default = null)
    {
        $property = $this->getProperty($name);

        return $property ? $property->value : $default;
    }

    public function hasProperty(string $name): bool
    {
        if (!$this->properties) return false;

        return isset($this->properties[$name]);
    }

    public function removeProperty(string $name): ContactInterface
    {
        if (!$this->hasProperty($name)) return $this;

        unset($this->properties[$name]);

        return $this;
    }

    public function setOwner(?array $data): ContactInterface
    {
        if (empty($data)) return $this;

        $this->owner = new ContactOwner($data);

        return $this;
    }

    public function setTags(?array $data): ContactInterface
    {
        if (empty($data)) return $this;

        $this->tags = new Collection();
        foreach ($data as $dt) {
            $tag = new ContactTag($dt);

            $this->tags[$tag->tag] = $tag;
        }

        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags ?? new Collection();
    }

    public function addTag(string $tag): ContactInterface
    {
        $tag = trim($tag);
        if (empty($tag)) return $this;

        if (empty($this->tags)) $this->tags = new Collection();

        $this->tags[$tag] = new ContactTag(['tag' => $tag]);

        return $this;
    }

    public function setSources(?array $data): ContactInterface
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'name' => $this->name,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'fullname' => $this->fullname,
            'name_sort' => $this->name_sort,
            'email' => $this->email,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
            'status' => $this->status,
            'sources' => $this->sources,
            'companyIds' => $this->companyIds,
            'contactIds' => $this->contactIds,
            'properties' => $this->properties,
            'listIds' => $this->listIds,
            'owner' => $this->owner,
            'entiy_group_name' => $this->entiy_group_name,
            'tags' => $this->tags,
            'broadcastIds' => $this->broadcastIds,
            'openedLinks' => $this->openedLinks,
            'emailProperties' => $this->emailProperties,
            'unsubscribeList' => $this->unsubscribeList,
            'emailBounceStatus' => $this->emailBounceStatus,
            'importedEntity' => $this->importedEntity,
            'forceCreate' => $this->forceCreate,
            'forceUpdate' => $this->forceUpdate,
            'score' => $this->score,
        ];
    }

    public function export(): array
    {
        $properties = [];

        if (!empty($this->firstname)) {
            $properties[] = (new ContactProperty([
                'name' =>  'name',
                'value' =>  trim($this->firstname),
                'field_type' =>  ContactProperty::FIELD_TYPE_TEXT,
                'is_searchable' =>  false,
                'type' =>  ContactProperty::TYPE_SYSTEM
            ]))->toArray();
        }

        if (!empty($this->lastname)) {
            $properties[] = (new ContactProperty([
                'name' =>  'last_name',
                'value' =>  trim($this->lastname),
                'field_type' =>  ContactProperty::FIELD_TYPE_TEXT,
                'is_searchable' =>  false,
                'type' =>  ContactProperty::TYPE_SYSTEM
            ]))->toArray();
        }

        if (!empty($this->email)) {
            $properties[] = (new ContactProperty([
                'name' =>  'email',
                'value' =>  trim($this->email),
                'field_type' =>  ContactProperty::FIELD_TYPE_TEXT,
                'is_searchable' =>  false,
                'type' =>  ContactProperty::TYPE_SYSTEM
            ]))->toArray();
        }

        /**
         * @var ContactProperty $property
         */
        foreach ($this->properties as $property) {
            if (in_array($property->name, ['name', 'last_name', 'email'])) continue;

            $properties[] = $property->toArray();
        }


        return $properties;
    }

    public function save(string $apiKey, bool $verifySSL = false): ContactSubmitResult
    {
        $contact = $this;

        if (empty($this->id)) {
            $tags = [];
            foreach ($contact->getTags() as $tag) {
                $tags[] = ['tag' => $tag->tag];
            }

            $data = [
                'score' => $this->score > 0 ? $this->score : 5,
                'properties' => $this->export(),
                'tags' => $tags,
            ];

            $result = ContactManager::create($apiKey, $data, $verifySSL);

            if ($result->isSuccess()) $this->id = $result->getContact()->id;

            return $result;
        }

        $data = [
            'id' => (int)$this->id,
            'properties' => $this->export(),
        ];

        $result = ContactManager::update($apiKey, $data, $verifySSL);

        return $result;
    }

    public function delete(string $apiKey, bool $verifySSL = false): Result
    {
        return ContactManager::delete($apiKey, (int)$this->id, $verifySSL);
    }

    public function addNote(string $apiKey, string $subject, string $content, bool $verifySSL = false): Result
    {
        return ContactManager::addNote($apiKey, (int)$this->id, $subject, $content, $verifySSL);
    }

    private function cleanValues(): ContactInterface
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