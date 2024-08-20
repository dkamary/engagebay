<?php

namespace Aika\Engagebay\Entities\Contact;

use Aika\Engagebay\Entities\Contact\Base\AbstractContact;

class Contact extends AbstractContact
{

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }
    
}
