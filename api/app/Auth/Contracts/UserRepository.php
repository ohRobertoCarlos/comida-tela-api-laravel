<?php

namespace App\Auth\Contracts;

use App\Models\BaseModel;

interface UserRepository
{
    public function findByEmail(string $email) : BaseModel|null;
}