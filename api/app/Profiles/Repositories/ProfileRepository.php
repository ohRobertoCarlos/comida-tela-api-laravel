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

    public function getByEstablishmentId(string $establishmentId) : BaseModel|null
    {
        return $this->getModel()->where('establishment_id', $establishmentId)->first();
    }
}
