<?php

namespace App\Menus\Repositories;

use App\Menus\Models\Menu;
use App\Models\BaseModel;
use App\Repositories\BaseRepository;

class MenuRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Menu();
    }

    public function getByEstablismentId(string $establishmentId) : BaseModel
    {
        return $this->getModel()->where('establishment_id', $establishmentId)->with('items')->first();
    }
}
