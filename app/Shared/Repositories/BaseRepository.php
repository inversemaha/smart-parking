<?php

namespace App\Shared\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    /**
     * The model instance.
     */
    protected Model $model;

    /**
     * Create a new repository instance.
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * Get the model class name.
     */
    abstract protected function getModelClass(): string;

    /**
     * Make model instance.
     */
    public function makeModel(): Model
    {
        $model = app()->make($this->getModelClass());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->getModelClass()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Get a new query builder instance.
     */
    protected function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Find a model by its primary key.
     */
    public function find(int $id): ?Model
    {
        return $this->query()->find($id);
    }

    /**
     * Find a model by its primary key or throw an exception.
     */
    public function findOrFail(int $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    /**
     * Find models by a column value.
     */
    public function findBy(string $column, $value): Collection
    {
        return $this->query()->where($column, $value)->get();
    }

    /**
     * Find a single model by a column value.
     */
    public function findOneBy(string $column, $value): ?Model
    {
        return $this->query()->where($column, $value)->first();
    }

    /**
     * Get all models.
     */
    public function all(): Collection
    {
        return $this->query()->get();
    }

    /**
     * Get all models with pagination.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage);
    }

    /**
     * Create a new model.
     */
    public function create(array $data): Model
    {
        return $this->query()->create($data);
    }

    /**
     * Update an existing model.
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model->fresh();
    }

    /**
     * Delete a model.
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Count all models.
     */
    public function count(): int
    {
        return $this->query()->count();
    }

    /**
     * Check if any models exist.
     */
    public function exists(): bool
    {
        return $this->query()->exists();
    }

    /**
     * Get the first model.
     */
    public function first(): ?Model
    {
        return $this->query()->first();
    }

    /**
     * Get the latest models.
     */
    public function latest(string $column = 'created_at'): Collection
    {
        return $this->query()->latest($column)->get();
    }

    /**
     * Get models where column is in array.
     */
    public function whereIn(string $column, array $values): Collection
    {
        return $this->query()->whereIn($column, $values)->get();
    }

    /**
     * Get models where column is not in array.
     */
    public function whereNotIn(string $column, array $values): Collection
    {
        return $this->query()->whereNotIn($column, $values)->get();
    }

    /**
     * Apply where clause to query.
     */
    public function where(string $column, $operator, $value = null): Collection
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        return $this->query()->where($column, $operator, $value)->get();
    }

    /**
     * Apply multiple where clauses to query.
     */
    public function whereMultiple(array $conditions): Collection
    {
        $query = $this->query();

        foreach ($conditions as $condition) {
            if (count($condition) === 2) {
                $query->where($condition[0], $condition[1]);
            } elseif (count($condition) === 3) {
                $query->where($condition[0], $condition[1], $condition[2]);
            }
        }

        return $query->get();
    }

    /**
     * Get models with relationships loaded.
     */
    public function with(array $relations): Collection
    {
        return $this->query()->with($relations)->get();
    }

    /**
     * Get models ordered by column.
     */
    public function orderBy(string $column, string $direction = 'asc'): Collection
    {
        return $this->query()->orderBy($column, $direction)->get();
    }

    /**
     * Insert multiple records.
     */
    public function insert(array $data): bool
    {
        return $this->query()->insert($data);
    }

    /**
     * Update or create a model.
     */
    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->query()->updateOrCreate($attributes, $values);
    }

    /**
     * Find or create a model.
     */
    public function firstOrCreate(array $attributes, array $values = []): Model
    {
        return $this->query()->firstOrCreate($attributes, $values);
    }
}
