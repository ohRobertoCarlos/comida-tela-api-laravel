<?php

namespace App\Profiles\Repositories;

use App\Profiles\Models\Profile;
use App\Models\BaseModel;
use App\Repositories\BaseRepository;

class ProfileRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Profile();
    }
}
