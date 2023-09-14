<?php

namespace App\Repositories;

use App\Contracts\Repository;
use App\Models\BaseModel;

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
}