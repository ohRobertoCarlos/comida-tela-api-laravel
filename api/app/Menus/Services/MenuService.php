<?php

namespace App\Menus\Services;

use App\Contracts\Repository;
use App\Items\Repositories\ItemRepository;
use App\Items\Services\ItemService;
use App\Menus\Repositories\MenuRepository;
use App\Models\BaseModel;
use Exception;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

class MenuService
{
    public function __construct(
        private MenuRepository $repository,
        private ItemService $itemService
    )
    {}

    public function getMenu(string $establishmentId) : BaseModel|null
    {
        return $this->repository->getByEstablismentId(establishmentId: $establishmentId);
    }

    public function addItem(string $establishmentId, array $data) : BaseModel
    {
        $menu = $this->getMenu(establishmentId: $establishmentId);
        if (empty($menu)) {
            throw new Exception('menu not found');
        }

        $itemSameTitleExists = $this->itemService
            ->sameTitleExists(menuId: $menu->id, title: $data['title']);
        if($itemSameTitleExists) {
            throw new Exception('Already exists item with this title in menu');
        }

        $data['cover_image_location'] = $this->storeImageItem(
            file: $data['cover_image'],
            menu: $menu
        );

        return $this->itemService->create(menu: $menu, data: $data);
    }

    private function storeImageItem(File|UploadedFile $file, BaseModel $menu) : string
    {
        return $this->itemService->storageImageItem(image: $file, menu: $menu);
    }

    public function updateItem(string $itemId, array $data) : bool
    {
        $item = $this->itemService->getItem(itemId: $itemId);
        if (empty($item)) {
            throw new Exception('item not found');
        }

        if (!empty($data['title'])) {
            $itemSameTitleExists = $this->itemService
            ->sameTitleExists(menuId: $item->menu_id, title: $data['title'], ignoredItemId: $item->id);

            if($itemSameTitleExists) {
                throw new Exception('Already exists item with this title in menu');
            }
        }

        if (!empty($data['cover_image'])) {
            $data['cover_image_location'] = $this->storeImageItem(
                file: $data['cover_image'],
                menu: $this->getMenu($item->menu_id)
            );
        }

        return $this->itemService->update(id: $item->id, data: $data);
    }

    public function deleteItem(string $itemId) : bool
    {
        return $this->itemService->delete(id: $itemId);
    }
}
