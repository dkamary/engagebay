<?php

namespace Aika\Engagebay\Managers;

use Aika\Engagebay\Http\ClientOption;
use Aika\Engagebay\Http\EngagebayClient;
use Aika\Engagebay\Transactions\ContactListResult;

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
}