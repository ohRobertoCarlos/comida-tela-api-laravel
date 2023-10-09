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
        private Repository|MenuRepository $repository = new MenuRepository(),
        private ItemService $itemService = new ItemService()
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

        $itemSameTitleExists = $this->getItemRepository()
            ->sameTitleExists(menu: $menu, title: $data['title']);
        if($itemSameTitleExists) {
            throw new Exception('Already exists item with this title in menu');
        }

        $data['menu_id'] = $menu->id;
        $data['cover_image_location'] = $this->storeImageItem(
            file: $data['cover_image'],
            menu: $menu
        );

        return $this->getItemRepository()->create(data: $data);
    }

    private function getItemRepository() : ItemRepository
    {
        return new ItemRepository();
    }

    private function storeImageItem(File|UploadedFile $file, BaseModel $menu) : string
    {
        return $this->itemService->storageImageItem(image: $file, menu: $menu);
    }
}
