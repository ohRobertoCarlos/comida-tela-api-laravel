<?php

namespace App\Establishments\Repositories;

use App\Establishments\Models\Establishment;
use App\Models\BaseModel;
use App\Repositories\BaseRepository;

class EstablishmentRepository extends BaseRepository
{
    public function __construct(
        protected BaseModel $model = new Establishment()
    )
    {}
}