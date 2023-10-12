<?php

namespace App\Contracts;

use App\Models\BaseModel;
use Illuminate\Support\Collection;

interface Repository
{
    public function create(array $data) : BaseModel;
    public function update(string $id, array $data) : bool;
    public function delete(string $id) : bool;
    public function all() : Collection;

    public function findById(string $id) : BaseModel|null;
}
