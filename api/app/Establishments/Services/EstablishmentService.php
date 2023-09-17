<?php

namespace App\Establishments\Services;

use App\Contracts\Repository;
use App\Establishments\Repositories\EstablishmentRepository;
use App\Models\BaseModel;
use Illuminate\Support\Collection;

class EstablishmentService
{
    public function __construct(
        private Repository $repository = new EstablishmentRepository()
    )
    {}

    public function getAll() : Collection
    {
        return $this->repository->all();
    }

    public function get(string $id) : BaseModel
    {
        return $this->repository->findById($id);
    }

    public function create(array $data) : BaseModel|null
    {
        return $this->repository->create($data);
    }

    public function update(string $id, array $data) : bool
    {
        return $this->repository->update($id, $data);
    }

    public function delete(string $id) : bool
    {
        return $this->repository->delete($id);
    }
}