<?php

namespace App\Contracts;

use App\Models\BaseModel;

interface Repository
{
    public function create(array $data) : BaseModel;
}