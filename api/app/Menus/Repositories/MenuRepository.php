<?php

namespace App\Menus\Repositories;

use App\Menus\Models\Menu;
use App\Models\BaseModel;
use App\Repositories\BaseRepository;

class MenuRepository extends BaseRepository
{
    public function __construct(
        protected BaseModel $model = new Menu()
    )
    {}
}
