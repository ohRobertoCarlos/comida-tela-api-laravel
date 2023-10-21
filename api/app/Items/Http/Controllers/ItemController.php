<?php

namespace App\Items\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Items\Http\Resources\Item;
use App\Items\Services\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Throwable;

class ItemController extends BaseController
{
    public function __construct(
        private ItemService $service
    ){}

    /**
    * @unauthenticated
    */
    public function index(string $establishmentId) : AnonymousResourceCollection
    {
        try {
            $items = $this->service->getItems(establishmentId: $establishmentId);
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        return Item::collection($items ?? []);
    }

    /**
    * @unauthenticated
    */
    public function show(string $establishmentId, string $itemId) : Item|JsonResponse
    {
        try {
            $item = $this->service->getItem(itemId: $itemId);
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        if (empty($item)) {
            return response()->json([
                'message' => __('items.item_not_found')
            ], 404);
        }

        return new Item($item);
    }
}