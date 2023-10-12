<?php

namespace App\Menus\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Items\Http\Requests\CreateItemRequest;
use App\Items\Http\Requests\UpdateItemRequest;
use App\Items\Http\Resources\Item;
use App\Menus\Http\Requests\UserIsOfEstablismentRequest;
use App\Menus\Http\Resources\Menu;
use App\Menus\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class MenuController extends BaseController
{
    public function __construct(
        private MenuService $service
    )
    {}

    public function show(UserIsOfEstablismentRequest $request, string $establishmentId) : Menu|JsonResponse
    {
        $menu = $this->service->getMenu(establishmentId: $establishmentId);
        if (empty($menu)) {
            return response()->json([
                'message' => __('menus.menu_not_found')
            ], 404);
        }

        return new Menu($menu);
    }

    public function addItem(CreateItemRequest $request, string $establishmentId) : JsonResponse|Item
    {
        try {
            $item = $this->service->addItem(
                establishmentId: $establishmentId,
                data: $request->validated()
            );
        } catch(Throwable $e) {
            Log::error($e->getMessage());
        }

        if (empty($item)) {
            return response()->json([
                'message' => __('menus.cold_not_create_item')
            ], 400);
        }

        return new Item($item);
    }

    public function updateItem(UpdateItemRequest $request, string $establishmentId, string $itemId) : JsonResponse
    {
        try {
            $itemUpdated = $this->service->updateItem(
                itemId : $itemId,
                data: $request->validated()
            );
        } catch(Throwable $e) {
            $itemUpdated = false;
            Log::error($e->getMessage());
        }

        if (!$itemUpdated) {
            return response()->json([
                'message' => __('menus.cold_not_update_item')
            ], 400);
        }

        return response()->json([
            'message' => __('menus.update_item_successfully')
        ]);
    }

    public function deleteItem(UserIsOfEstablismentRequest $request, string $establishmentId, string $itemId) : JsonResponse
    {
        try {
            $itemDeleted = $this->service->deleteItem(itemId: $itemId);
        } catch(Throwable $e) {
            Log::error($e->getMessage());
            $itemDeleted = false;
        }

        if(!$itemDeleted) {
            return response()->json([
                'message' => __('menus.cold_not_delete_item')
            ], 400);
        }

        return response()->json([
            'message' => __('menus.item_deleted_successfully')
        ]);
    }
}
