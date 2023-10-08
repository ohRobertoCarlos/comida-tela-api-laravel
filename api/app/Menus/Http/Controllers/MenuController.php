<?php

namespace App\Menus\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Menus\Http\Requests\UserIsOfEstablismentRequest;
use App\Menus\Http\Resources\Menu;
use App\Menus\Services\MenuService;
use Illuminate\Http\JsonResponse;

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
}
