<?php

namespace Aika\Engagebay\Managers;

use Aika\Engagebay\Http\ClientOption;
use Aika\Engagebay\Http\EngagebayClient;
use Aika\Engagebay\Transactions\ContactListResult;
use Aika\Engagebay\Transactions\NoteListResult;

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
}