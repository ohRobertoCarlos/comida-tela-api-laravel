<?php

namespace App\Repositories;

use App\Contracts\Repository;
use App\Models\BaseModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseRepository implements Repository
{
    public function __construct(
        protected BaseModel $model
    )
    {}

    public function getModel() : BaseModel
    {
        return $this->model;
    }

    public function create(array $data) : BaseModel
    {
        return $this->getModel()->create($data);
    }

    public function update(string $id, array $data) : bool
    {
        return (bool) $this->findById($id)?->update($data);
    }

    public function delete(string $id): bool
    {
        return (bool) $this->findById($id)?->delete();
    }

    public function findById(string $id): BaseModel|null
    {
        return $this->getModel()->find($id);
    }

    public function all(): Collection
    {
        return $this->getModel()->all();
    }

    public function updateRelationships(BaseModel $model, array $relations) : void
    {
        foreach ($relations as $relation => $values) {
            try {
                $model->$relation()->sync($values);
            } catch (Throwable $e) {
                Log::error($e->getMessage());

                throw $e;
            }
        }
    }

    public function findByIdWithRelations(string $id, array $relations): BaseModel|null
    {
        return $this->getModel()
            ->with($relations)
            ->find($id);
    }
}
