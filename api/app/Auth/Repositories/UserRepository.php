<?php

namespace App\Auth\Repositories;

use App\Auth\Contracts\UserRepository as Repository;
use App\Models\BaseModel;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements Repository
{
    public function __construct()
    {
        $this->model = new User();
    }

    public function findByEmail(string $email) : BaseModel|null
    {
        return $this->getModel()->where('email', $email)->first();
    }

    public function getAllByEstablishmentId(string $establishmentId) : Collection
    {
        return $this->getModel()->where('establishment_id', $establishmentId)->get();
    }
}
