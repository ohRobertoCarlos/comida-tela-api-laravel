<?php

namespace App\Establishments\Repositories;

use App\Establishments\Models\Establishment;
use App\Menus\Repositories\MenuRepository;
use App\Models\BaseModel;
use App\Profiles\Repositories\ProfileRepository;
use App\Repositories\BaseRepository;

class EstablishmentRepository extends BaseRepository
{
    public function __construct(
        protected BaseModel $model = new Establishment()
    )
    {}

    public function createMenu(BaseModel $establishment, string $qrCodeImagePath) : void
    {
        if (empty($establishment)) {
            throw new \InvalidArgumentException('Establishment not informed');
        }

        $this->getMenuRepository()->create([
            'establishment_id' => $establishment->id,
            'qr_code_image_path' => $qrCodeImagePath
        ]);
    }

    public function createProfile(BaseModel $establishment) : void
    {
        if (empty($establishment)) {
            throw new \InvalidArgumentException('Establishment not informed');
        }

        $this->getProfileRepository()->create([
            'establishment_id' => $establishment->id,
        ]);
    }

    private function getMenuRepository() : BaseRepository
    {
        return new MenuRepository();
    }

    private function getProfileRepository() : BaseRepository
    {
        return new ProfileRepository();
    }
}
