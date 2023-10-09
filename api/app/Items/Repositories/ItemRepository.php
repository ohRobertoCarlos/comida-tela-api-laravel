<?php

namespace App\Items\Repositories;

use App\Items\Models\Item;
use App\Menus\Models\Menu;
use App\Models\BaseModel;
use App\Repositories\BaseRepository;

class ItemRepository extends BaseRepository
{
    public function __construct(
        protected BaseModel $model = new Item()
    )
    {}

    public function sameTitleExists(Menu $menu, string $title, string|null $ignoredItemId = null) : bool
    {
        return $this->getModel()
        ->where('menu_id', $menu->id)
        ->where('title', $title)
        ->where('id', '<>', $ignoredItemId)
        ->exists();
    }
}
