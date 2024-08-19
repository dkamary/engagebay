<?php

namespace Aika\Engagebay\Transactions;

use Aika\Engagebay\Entities\Contact\Contact;

class ContactSubmitResult extends Result
{
    const ALREADY_EXISTS = 100;

    public function __construct(int $status = self::UNKNOW, string $message = null, ?Contact $contact = null)
    {
        parent::__construct($status, $message, $contact);
    }

    public function getStatusText(): string
    {
        if ($this->status == self::ALREADY_EXISTS) return 'already exists';

        return parent::getStatusText();
    }

    public function isWarning(): bool
    {
        if ($this->status == self::ALREADY_EXISTS) return true;

        return parent::isWarning();
    }

    public function getContact(): ?Contact
    {
        return $this->data;
    }

    public function setContact(?Contact $contact = null): self
    {
        $this->data = $contact;

        return $this;
    }

    public static function createFromResult(Result $result): ContactSubmitResult
    {
        $theResult = new ContactSubmitResult(
            $result->getStatus(),
            $result->getMessage()
        );
        
        if (is_array($result->getData())) {
            $contact = new Contact($result->getData());

            $theResult->setContact($contact);
        }

        if (
            $theResult->getStatus() == ContactSubmitResult::WARNING 
            && strpos($theResult->getMessage(), 'A Contact with this email address') !== false
            && strpos($theResult->getMessage(), 'already exists') !== false
        ) {
            $theResult
                ->setStatus(self::ALREADY_EXISTS)
                ->setMessage('A Contact with this email address already exists');
        }

        return $theResult;
    }
}