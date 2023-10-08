<?php

namespace App\Menus\Services;

use App\Contracts\Repository;
use App\Menus\Repositories\MenuRepository;
use App\Models\BaseModel;

class MenuService
{
    public function __construct(
        private Repository|MenuRepository $repository = new MenuRepository()
    )
    {}

    public function getMenu(string $establishmentId) : BaseModel|null
    {
        return $this->repository->getByEstablismentId(establishmentId: $establishmentId);
    }
}
