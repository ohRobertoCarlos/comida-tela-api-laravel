<?php

namespace App\Items\Services;

use App\Contracts\Repository;
use App\Items\Repositories\ItemRepository;
use App\Menus\Repositories\MenuRepository;
use App\Models\BaseModel;
use App\Ratings\Enums\Feedback;
use App\Ratings\Services\RatingService;
use Countable;
use Exception;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ItemService
{
    public function __construct(
        private ItemRepository $repository,
        private MenuRepository $menuRepository,
        private RatingService $ratingService
    )
    {}

    public function create(BaseModel $menu, array $data) : BaseModel
    {
        $data['menu_id'] = $menu->id;
        return $this->repository->create(data: $data);
    }

    public function update(string $id, array $data) : bool
    {
        return $this->repository->update(id: $id, data: $data);
    }

    public function delete(string $id) : bool
    {
        return $this->repository->delete(id: $id);
    }

    public function storageImageItem(File|UploadedFile $image, BaseModel $menu) : string
    {
        try {
            $path = Storage::disk(env('PUBLIC_FILESYSTEM_DISK', 'public'))->put('menus/' . $menu->id, $image);
        } catch(Throwable $e) {
            Log::error($e->getMessage());
            throw new \Exception('Unable to save image');
        }

        return $path;
    }

    public function getItem(string $itemId) : BaseModel|null
    {
        return $this->repository->findById(id: $itemId);
    }

    public function sameTitleExists(string $menuId, string $title, string|null $ignoredItemId = null) : bool
    {
        return $this->repository
            ->sameTitleExists(menuId: $menuId, title: $title, ignoredItemId: $ignoredItemId);
    }

    public function getItems(string $establishmentId, array $queryParams = []) : Collection
    {
        $menu = $this->menuRepository->getByEstablismentId($establishmentId);
        if (empty($menu)) {
            return collect([]);
        }

        return $this->repository->allFromMenuWithSearch(menuId: $menu->id, search: $queryParams);
    }

    public function like(string $establishmentId, string $itemId, array $data) : void
    {
        $item = $this->getItem($itemId);
        $menu = $this->menuRepository->getByEstablismentId($establishmentId);

        if (empty($item) || $item->menu_id !== $menu->id) {
            throw new Exception('Item not found');
        }

        DB::transaction(function() use ($item, $data, $establishmentId) {
            $this->ratingService->store($data, $establishmentId);

            $item->likes++;
            $item->save();
        });
    }

    public function unlike(string $establishmentId, string $itemId, array $data) : void
    {
        $item = $this->getItem($itemId);
        $menu = $this->menuRepository->getByEstablismentId($establishmentId);

        if (empty($item) || $item->menu_id !== $menu->id) {
            throw new Exception('Item not found');
        }

        $data['feedback'] = Feedback::Negative->value;

        DB::transaction(function() use ($item, $data, $establishmentId) {
            $this->ratingService->store($data, $establishmentId);

            $item->not_likes++;
            $item->save();
        });
    }
}
