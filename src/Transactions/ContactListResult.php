<?php

namespace Aika\Engagebay\Transactions;

use Aika\Engagebay\Entities\Contact\Contact;
use Aika\Engagebay\Utils\Collection;

class ContactListResult extends ListResult
{
    protected $cursor = null;
    /**
     * Constructor
     *
     * @param int $status
     * @param string|null $message
     * @param Collection<Contact>|Contact[]|array|null $collection
     */
    public function __construct(int $status = self::UNKNOW, ?string $message = null, mixed $collection = null, ?string $cursor = null)
    {
        parent::__construct($status, $message);

        if ($collection && $collection->count() > 0) {
            foreach ($collection as $contact) {
            
                if (is_array($contact)) $contact = new Contact($contact);
    
                $this->addContact($contact);
    
            }
        }

        $this->cursor = $cursor;
    }

    /**
     * Get contact list
     *
     * @return Collection<Contact>|Contact[]|array|null
     */
    public function getContactList(): ?Collection
    {
        return $this->data;
    }

    /**
     * Set contact list
     *
     * @param Collection<Contact>|Contact[]|array $collection
     * @return self
     */
    public function setContactList(Collection $collection): self
    {
        $this->data = $collection;

        return $this;
    }

    public function addContact(Contact $contact): self
    {
        if(($this->data instanceof Collection) == false) $this->data = new Collection();
        $this->data[(int)$contact->id] = $contact;

        return $this;
    }

    public function removeContact(int $contactId): self
    {
        if (empty($this->data) || !isset($this->data[$contactId])) return $this;
        unset($this->data[$contactId]);

        return $this;
    }

    public function getFirstContact(): ?Contact
    {
        if (empty($this->data)) return null;

        foreach ($this->data as $contact) {
            return $contact;
        }

        return null;
    }

    public function getLastContact(): ?Contact
    {
        if (empty($this->data)) return null;

        $last = null;
        foreach ($this->data as $contact) $last = $contact;

        return $last;
    }

    public function getCursor(): ?string
    {
        return $this->cursor;
    }

    public function setCursor(string $cursor): self
    {
        $this->cursor = $cursor;

        return $this;
    }

    public static function createFromResult(Result $result): ContactListResult
    {
        $list = new ContactListResult();
        $list
            ->setStatus($result->getStatus() != ContactListResult::DONE ? $result->getStatus() : ContactListResult::FOUND)
            ->setMessage($result->getMessage());
        
        if (is_array($result->getData())) {
            foreach ($result->getData() as $data) {

                if (!is_array($data)) {
                    $contact = new Contact($result->getData());
                    $list->addContact($contact);
                    break; 
                }

                $contact = new Contact($data);
                $list->addContact($contact);

                if (isset($data['cursor'])) $list->setCursor($data['cursor']);
            }
        }

        if ($list->getStatus() == self::FOUND && $list->getContactList()->count() == 0) $list->setStatus(self::NOT_FOUND);

        return $list;
    }
}