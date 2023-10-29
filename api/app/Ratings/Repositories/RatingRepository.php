<?php

namespace App\Ratings\Repositories;

use App\Ratings\Models\Rating;
use App\Repositories\BaseRepository;

class RatingRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Rating();
    }
}
