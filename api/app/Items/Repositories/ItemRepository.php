<?php

namespace App\Items\Repositories;

use App\Items\Models\Item;
use App\Menus\Models\Menu;
use App\Models\BaseModel;
use App\Repositories\BaseRepository;

class ItemRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Item();
    }

    public function sameTitleExists(string $menuId, string $title, string|null $ignoredItemId = null) : bool
    {
        return $this->getModel()
        ->where('menu_id', $menuId)
        ->where('title', $title)
        ->where('id', '<>', $ignoredItemId)
        ->exists();
    }
}
