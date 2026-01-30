<?php

namespace App\Shared\DTOs;

class ApiResponseDTO
{
    public function __construct(
        public readonly bool $success,
        public readonly string $message,
        public readonly mixed $data = null,
        public readonly ?array $errors = null,
        public readonly ?array $meta = null
    ) {}

    /**
     * Create success response.
     */
    public static function success(string $message, mixed $data = null, ?array $meta = null): self
    {
        return new self(
            success: true,
            message: $message,
            data: $data,
            meta: $meta
        );
    }

    /**
     * Create error response.
     */
    public static function error(string $message, ?array $errors = null): self
    {
        return new self(
            success: false,
            message: $message,
            errors: $errors
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        $response = [
            'success' => $this->success,
            'message' => $this->message,
        ];

        if ($this->data !== null) {
            $response['data'] = $this->data;
        }

        if ($this->errors !== null) {
            $response['errors'] = $this->errors;
        }

        if ($this->meta !== null) {
            $response['meta'] = $this->meta;
        }

        return $response;
    }
}
