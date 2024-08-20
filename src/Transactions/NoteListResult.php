<?php

namespace Aika\Engagebay\Transactions;

use Aika\Engagebay\Entities\Contact\Contact;
use Aika\Engagebay\Entities\Contact\ContactNote;
use Aika\Engagebay\Utils\Collection;

class NoteListResult extends ListResult
{
    /**
     * Constructor
     *
     * @param int $status
     * @param string|null $message
     * @param Collection<Contact>|Contact[]|array|null $collection
     */
    public function __construct(int $status = self::UNKNOW, ?string $message = null, mixed $collection = null)
    {
        parent::__construct($status, $message);

        if ($collection && $collection->count() > 0) {
            foreach ($collection as $note) {
            
                if (is_array($note)) $note = new Contact($note);
    
                $this->addNote($note);
    
            }
        }

    }

    /**
     * Get contact list
     *
     * @return Collection<ContactNote>|ContactNote[]|array|null
     */
    public function getNoteList(): ?Collection
    {
        return $this->data;
    }

    /**
     * Set contact list
     *
     * @param Collection<ContactNote>|ContactNote[]|array $collection
     * @return self
     */
    public function setNoteList(Collection $collection): self
    {
        $this->data = $collection;

        return $this;
    }

    public function addNote(ContactNote $note): self
    {
        if(($this->data instanceof Collection) == false) $this->data = new Collection();
        $this->data[(int)$note->id] = $note;

        return $this;
    }

    public function removeNote(int $noteId): self
    {
        if (empty($this->data) || !isset($this->data[$noteId])) return $this;
        unset($this->data[$noteId]);

        return $this;
    }

    public function getFirstNote(): ?Contact
    {
        if (empty($this->data)) return null;

        foreach ($this->data as $note) {
            return $note;
        }

        return null;
    }

    public function getLastNote(): ?Contact
    {
        if (empty($this->data)) return null;

        $last = null;
        foreach ($this->data as $note) $last = $note;

        return $last;
    }

    public static function createFromResult(Result $result): NoteListResult
    {
        // var_dump($result); die;
        $list = new NoteListResult();
        $list
            ->setStatus($result->getStatus())
            ->setMessage($result->getMessage());
        
        if (is_array($result->getData())) {
            
            foreach ($result->getData() as $data) {

                if (!is_array($data)) {
                    $note = new ContactNote($result->getData());
                    $list->addNote($note);
                    break; 
                }

                $note = new ContactNote($data);
                $list->addNote($note);

            }

            if ($result->getStatus() == NoteListResult::DONE) {
                $list->setStatus(count($result->getData()) > 0 ? NoteListResult::FOUND : NoteListResult::NOT_FOUND);
            }
        }

        return $list;
    }
}