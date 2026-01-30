<?php

namespace App\Domains\User\DTOs;

class VisitorLoginData
{
    public function __construct(
        public string $login, // Can be email or mobile
        public string $password,
        public bool $remember = false,
        public ?string $deviceName = null
    ) {}

    public function toArray(): array
    {
        return [
            'login' => $this->login,
            'password' => $this->password,
            'remember' => $this->remember,
            'device_name' => $this->deviceName,
        ];
    }
}
