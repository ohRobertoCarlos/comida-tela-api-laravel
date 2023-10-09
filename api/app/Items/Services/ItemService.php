<?php

namespace App\Items\Services;

use App\Contracts\Repository;
use App\Items\Repositories\ItemRepository;
use App\Models\BaseModel;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ItemService
{
    public function __construct(
        private ItemRepository $repository = new ItemRepository()
    )
    {}

    public function create(BaseModel $menu, array $data) : BaseModel
    {
        $data['menu_id'] = $menu->id;
        return $this->repository->create($data);
    }

    public function storageImageItem(File|UploadedFile $image, BaseModel $menu) : string
    {
        try {
            $path = Storage::putFile('menus/' . $menu->id, $image);
        } catch(Throwable $e) {
            Log::error($e->getMessage());
            throw new \Exception('Unable to save image');
        }

        return $path;
    }
}
