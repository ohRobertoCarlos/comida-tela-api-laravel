<?php

namespace App\Repositories;

use App\Contracts\Repository;
use App\Models\BaseModel;
use Illuminate\Support\Collection;

abstract class BaseRepository implements Repository
{
    protected BaseModel $model;

    protected function getModel() : BaseModel
    {
        return $this->model;
    }

    public function create(array $data) : BaseModel
    {
        return $this->getModel()->create($data);
    }

    public function update(string $id, array $data) : bool
    {
        return $this->findById($id)->update($data);
    }

    public function delete(string $id): bool
    {
        return $this->findById($id)->delete();
    }

    public function findById(string $id): BaseModel
    {
        return $this->getModel()->find($id);
    }

    public function all(): Collection
    {
        return $this->getModel()->all();
    }
}