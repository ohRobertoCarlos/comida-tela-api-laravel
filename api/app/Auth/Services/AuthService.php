<?php

namespace App\Auth\Services;

use App\Auth\Contracts\UserRepository as ContractsUserRepository;
use App\Auth\Repositories\UserRepository;
use App\Contracts\Repository;
use App\Models\BaseModel;
use InvalidArgumentException;

class AuthService
{
    public function __construct(
        public ContractsUserRepository|Repository $userRespository = new UserRepository()
    )
    {}

    public function getUserByEmail(string $email) : BaseModel|null
    {
        if (empty($email)) {
            throw new InvalidArgumentException(__('auth.invalid_email'));
        }

        return $this->userRespository->findByEmail($email);
    }

    public function createUser(array $data) : BaseModel|null
    {
        return $this->userRespository->create($data);
    }
}