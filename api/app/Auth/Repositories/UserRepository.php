<?php

namespace App\Auth\Repositories;

use App\Auth\Contracts\UserRepository as Repository;
use App\Models\BaseModel;
use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements Repository
{
    public function __construct(
        protected BaseModel $model = new User()
    )
    {}

    public function findByEmail(string $email) : BaseModel|null
    {
        return $this->getModel()->where('email', $email)->first();
    }
}