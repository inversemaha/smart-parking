<?php

namespace App\Domains\User\DTOs;

class VisitorRegistrationData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $mobile,
        public string $password,
        public ?string $language = 'en',
        public ?string $deviceName = null
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'password' => $this->password,
            'language' => $this->language,
            'device_name' => $this->deviceName,
        ];
    }
}
