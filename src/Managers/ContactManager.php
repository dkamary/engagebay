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
    public static function getContactList(string $apiKey, ?string $cursor = null, int $pageSize = 10, string $sortKey = '-created_time'): ContactListResult
    {
        $uri = 'https://app.engagebay.com/dev/api/panel/subscribers';
        
        $options = new ClientOption();
        $options
            ->verifySSL(false)
            ->addFormParams('page_size', $pageSize)
            ->addFormParams('sort_key', $sortKey);
        // var_dump($options->getOptions()); exit;

        if ($cursor) $options->addFormParams('cursor', $cursor);

        $client = new EngagebayClient($apiKey);
        $result = ContactListResult::createFromResult($client->post($uri, $options));

        return $result;
    }

    public static function getContactById(string $apiKey, int $contactId): ContactListResult
    {
        $uri = 'https://app.engagebay.com/dev/api/panel/subscribers/' . $contactId;
        
        $options = new ClientOption();
        $options
            ->verifySSL(false);

        $client = new EngagebayClient($apiKey);
        $result = ContactListResult::createFromResult($client->get($uri, $options));

        return $result;
    }

    public static function getContactByEmail(string $apiKey, string $email): ContactListResult
    {
        $uri = 'https://app.engagebay.com/dev/api/panel/subscribers/contact-by-email/' . $email;

        $options = new ClientOption();
        $options->verifySSL(false);

        $client = new EngagebayClient($apiKey);
        $result = ContactListResult::createFromResult($client->get($uri, $options));

        return $result;
    }

    public static function searchContact(string $apiKey, string $search): ContactListResult
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
            ->verifySSL(false)
            ->addQuery('q', $search)
            ->addQuery('type', 'Subscriber');

        $client = new EngagebayClient($apiKey);
        $result = ContactListResult::createFromResult($client->get($uri, $options));

        return $result;
    }

    public static function getContactNotes(string $apiKey, int $contactId): NoteListResult
    {
        $uri = 'https://app.engagebay.com/dev/api/panel/notes/' . $contactId;

        $options = new ClientOption();
        $options
            ->verifySSL(false);

        $client = new EngagebayClient($apiKey);
        $result = NoteListResult::createFromResult($client->get($uri, $options));

        return $result;
    }

    public static function createContact(string $apiKey, Contact $contact): ContactSubmitResult
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

        return self::create($apiKey, $data);
    }

    public static function create(string $apiKey, array $data): ContactSubmitResult
    {
        $uri = 'https://app.engagebay.com/dev/api/panel/subscribers/subscriber';

        $options = new ClientOption();
        $options
            ->verifySSL(false)
            ->addHeader('Content-Type', EngagebayClient::MIME_JSON)
            ->setJson($data);

        $client = new EngagebayClient($apiKey);
        $result = ContactSubmitResult::createFromResult($client->post($uri, $options));

        return $result;
    }

    public static function updateContact(string $apiKey, Contact $contact): ContactSubmitResult
    {
        /**
         * TODO ContactID a verifier car tjs a 0
         */
        $data = [
            'id' => $contact->id,
            'properties' => $contact->export(),
        ];

        return self::update($apiKey, $data);
    }

    public static function update(string $apiKey, array $data): ContactSubmitResult
    {
        $uri = 'https://app.engagebay.com/dev/api/panel/subscribers/update-partial';

        $options = new ClientOption();
        $options
            ->verifySSL(false)
            ->addHeader('Content-Type', EngagebayClient::MIME_JSON)
            ->setJson($data);

        $client = new EngagebayClient($apiKey);
        $result = ContactSubmitResult::createFromResult($client->put($uri, $options));

        return $result; 
    }
}