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

}
