<?php

namespace Aika\Engagebay\Entities\Contact;

class ContactOwner
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var int $domain_id
     */
    public $domain_id;

    /**
     * @var string $email
     */
    public $email;

    /**
     * @var string $password_decrypted
     */
    public $password_decrypted;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var int $created_time
     */
    public $created_time;

    /**
     * @var int $updated_time
     */
    public $updated_time;

    /**
     * @var bool $is_admin
     */
    public $is_admin;

    /**
     * @var bool $is_verified
     */
    public $is_verified;

    /**
     * @var bool $is_owner
     */
    public $is_owner;

    /**
     * @var string $job_title
     */
    public $job_title;

    /**
     * @var string $role
     */
    public $role;

    /**
     * @var string $phone_number
     */
    public $phone_number;

    /**
     * @var string $language
     */
    public $language;
    /**
     * @var string $time_zone
     */
    public $time_zone;

    /**
     * @var int $time_zone_offset
     */
    public $time_zone_offset;

    /**
     * @var int $loggedin_time
     */
    public $loggedin_time;

    /**
     * @var string $misc_info
     */
    public $misc_info;

    /**
     * @var bool $is_signup_process_completed
     */
    public $is_signup_process_completed;

    /**
     * @var string $profile_img_url
     */
    public $profile_img_url;

    /**
     * @var string $category
     */
    public $category;

    /**
     * @var string $signupSource
     */
    public $signupSource;

    /**
     * @var int $domainId
     */
    public $domainId;

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    public function hydrate(array $data): self
    {
        $this->id = intval($data['id'] ?? null);
        $this->domain_id = intval($data['domain_id'] ?? null);
        $this->email = $data['email'] ?? null;
        $this->password_decrypted = $data['password_decrypted'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->created_time = intval($data['created_time'] ?? null);
        $this->updated_time = intval($data['updated_time'] ?? null);
        $this->is_admin = boolval($data['is_admin'] ?? null);
        $this->is_verified = boolval($data['is_verified'] ?? null);
        $this->is_owner = boolval($data['is_owner'] ?? null);
        $this->job_title = $data['job_title'] ?? null;
        $this->role = $data['role'] ?? null;
        $this->phone_number = $data['phone_number'] ?? null;
        $this->language = $data['language'] ?? null;
        $this->time_zone = $data['time_zone'] ?? null;
        $this->time_zone_offset = intval($data['time_zone_offset'] ?? null);
        $this->loggedin_time = $data['loggedin_time'] ?? null;
        $this->misc_info = $data['misc_info'] ?? null;
        $this->is_signup_process_completed = boolval($data['is_signup_process_completed'] ?? null);
        $this->profile_img_url = $data['profile_img_url'] ?? null;
        $this->category = $data['category'] ?? null;
        $this->signupSource = $data['signupSource'] ?? null;
        $this->domainId = intval($data['domainId'] ?? null);

        return $this;
    }
}
