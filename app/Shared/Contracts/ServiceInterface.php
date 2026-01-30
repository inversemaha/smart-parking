<?php

namespace App\Shared\Contracts;

interface ServiceInterface
{
    /**
     * Process business logic.
     */
    public function process(array $data);

    /**
     * Validate business rules.
     */
    public function validate(array $data): array;

    /**
     * Handle domain events.
     */
    public function handleEvent(string $event, array $data): bool;
}
