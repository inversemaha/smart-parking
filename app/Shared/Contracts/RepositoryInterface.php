<?php

namespace App\Shared\Contracts;

interface RepositoryInterface
{
    /**
     * Find a resource by ID.
     */
    public function find(int $id);

    /**
     * Create a new resource.
     */
    public function create(array $data);

    /**
     * Update an existing resource.
     */
    public function update($model, array $data);

    /**
     * Delete a resource.
     */
    public function delete($model): bool;

    /**
     * Get all resources with optional filters.
     */
    public function getAll(array $filters = []);

    /**
     * Get paginated results.
     */
    public function paginate(array $filters = [], int $perPage = 15);

    /**
     * Count total resources.
     */
    public function count(array $filters = []): int;
}
