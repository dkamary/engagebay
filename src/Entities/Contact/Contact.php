<?php

namespace Aika\Engagebay\Entities\Contact;

use Aika\Engagebay\Entities\Contact\Base\AbstractContact;
use Aika\Engagebay\Managers\ContactManager;
use Aika\Engagebay\Transactions\ContactSubmitResult;
use Aika\Engagebay\Transactions\Result;

class Contact extends AbstractContact
{

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
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
}
