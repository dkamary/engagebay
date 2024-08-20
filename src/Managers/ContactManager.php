<?php

namespace Aika\Engagebay\Managers;

use Aika\Engagebay\Entities\Contact\Contact;
use Aika\Engagebay\Entities\Contact\ContactTag;
use Aika\Engagebay\Http\ClientOption;
use Aika\Engagebay\Http\EngagebayClient;
use Aika\Engagebay\Transactions\ContactListResult;
use Aika\Engagebay\Transactions\ContactSubmitResult;
use Aika\Engagebay\Transactions\NoteListResult;
use Aika\Engagebay\Transactions\Result;

class ContactManager
{
    public static function getContactList(string $apiKey, ?string $cursor = null, int $pageSize = 10, string $sortKey = '-created_time', bool $verifySSL = false): ContactListResult
    {
        $uri = 'https://app.engagebay.com/dev/api/panel/subscribers';
        
        $options = new ClientOption();
        $options
            ->verifySSL($verifySSL)
            ->addFormParams('page_size', $pageSize)
            ->addFormParams('sort_key', $sortKey);
        // var_dump($options->getOptions()); exit;

        if ($cursor) $options->addFormParams('cursor', $cursor);

        $client = new EngagebayClient($apiKey);
        $result = ContactListResult::createFromResult($client->post($uri, $options));

        return $result;
    }

    public static function getContactById(string $apiKey, int $contactId, bool $verifySSL = false): ContactListResult
    {
        $uri = 'https://app.engagebay.com/dev/api/panel/subscribers/' . $contactId;
        
        $options = new ClientOption();
        $options
            ->verifySSL($verifySSL);

        $client = new EngagebayClient($apiKey);
        $result = ContactListResult::createFromResult($client->get($uri, $options));

        return $result;
    }

    public static function getContactByEmail(string $apiKey, string $email, bool $verifySSL = false): ContactListResult
    {
        $uri = 'https://app.engagebay.com/dev/api/panel/subscribers/contact-by-email/' . $email;

        $options = new ClientOption();
        $options->verifySSL($verifySSL);

        $client = new EngagebayClient($apiKey);
        $result = ContactListResult::createFromResult($client->get($uri, $options));

        return $result;
    }

    public static function searchContact(string $apiKey, string $search, bool $verifySSL = false): ContactListResult
    {
        $uri = 'https://app.engagebay.com/dev/api/search';

        $search = trim($search);
        if (empty($search)) {

            return new ContactListResult(
                ContactListResult::REFUSED,
                'Empty search'
            );
        }

        $options = new ClientOption();
        $options
            ->verifySSL($verifySSL)
            ->addQuery('q', $search)
            ->addQuery('type', 'Subscriber');

        $client = new EngagebayClient($apiKey);
        $result = ContactListResult::createFromResult($client->get($uri, $options));

        return $result;
    }

    public static function getContactNotes(string $apiKey, int $contactId, bool $verifySSL = false): NoteListResult
    {
        $uri = 'https://app.engagebay.com/dev/api/panel/notes/' . $contactId;

        $options = new ClientOption();
        $options
            ->verifySSL($verifySSL);

        $client = new EngagebayClient($apiKey);
        $result = NoteListResult::createFromResult($client->get($uri, $options));

        return $result;
    }

    public static function createContact(string $apiKey, Contact &$contact, bool $verifySSL = false): ContactSubmitResult
    {
        $tags = [];
        foreach ($contact->getTags() as $tag) {
            $tags[] = ['tag' => $tag->tag];
        }

        $data = [
            'score' => $contact->score > 0 ? $contact->score : 5,
            'properties' => $contact->export(),
            'tags' => $tags,
        ];

        $result = self::create($apiKey, $data, $verifySSL);
        if ($result->isSuccess()) $contact = $result->getContact();

        return $result;
    }

    public static function create(string $apiKey, array $data, bool $verifySSL = false): ContactSubmitResult
    {
        if (empty($data['properties'] ?? null)) {
            
            return (new ContactSubmitResult(
                ContactSubmitResult::REFUSED,
                'Contact Properties is not defined!'
            ))->setData($data);
        }

        if (!is_array($data['properties'])) {
            
            return (new ContactSubmitResult(
                ContactSubmitResult::REFUSED,
                'Contact Properties must be an array!'
            ))->setData($data);
        }

        $uri = 'https://app.engagebay.com/dev/api/panel/subscribers/subscriber';

        $options = new ClientOption();
        $options
            ->verifySSL($verifySSL)
            ->addHeader('Content-Type', EngagebayClient::MIME_JSON)
            ->setJson($data);

        $client = new EngagebayClient($apiKey);
        $result = ContactSubmitResult::createFromResult($client->post($uri, $options));

        return $result;
    }

    public static function updateContact(string $apiKey, Contact &$contact, bool $synchContact = true, bool $verifySSL = false): ContactSubmitResult
    {
        if (empty($contact->id)) return new ContactSubmitResult(
            ContactSubmitResult::NOTHING_HAPPENED,
            'Contact ID is not defined',
            $contact
        );

        $data = [
            'id' => $contact->id,
            'properties' => $contact->export(),
        ];

        $result = self::update($apiKey, $data, $verifySSL);

        if ($synchContact && $result->isSuccess()) {
            $contact = $result->getContact();
        }

        return $result;
    }

    public static function update(string $apiKey, array $data, bool $verifySSL = false): ContactSubmitResult
    {
        if (empty($data['id'] ?? null)) {
            
            return (new ContactSubmitResult(
                ContactSubmitResult::REFUSED,
                'Contact ID is not defined!'
            ))->setData($data);
        }

        $uri = 'https://app.engagebay.com/dev/api/panel/subscribers/update-partial';

        $options = new ClientOption();
        $options
            ->verifySSL($verifySSL)
            ->addHeader('Content-Type', EngagebayClient::MIME_JSON)
            ->setJson($data);

        $client = new EngagebayClient($apiKey);
        $result = ContactSubmitResult::createFromResult($client->put($uri, $options));

        return $result; 
    }

    public static function delete(string $apiKey, int $contactId, bool $verifySSL = false): Result
    {
        if (empty($contactId)) {
            
            return new Result(
                ContactSubmitResult::REFUSED,
                'Contact ID is not defined!'
            );
        }

        $uri = 'https://app.engagebay.com/dev/api/panel/subscribers/' . $contactId;

        $options = new ClientOption();
        $options->verifySSL($verifySSL);

        $client = new EngagebayClient($apiKey);
        $result = $client->delete($uri, $options);

        return $result;
    }
}