<?php

namespace App\Categories\Http\Controllers;

use App\Categories\Exceptions\CategorySameNameAlreadyExistsException;
use App\Categories\Exceptions\RemoveItemsCategoryDeleteException;
use App\Categories\Http\Requests\StoreCategoryRequest;
use App\Categories\Http\Requests\UpdateCategoryRequest;
use App\Categories\Http\Resources\Category;
use App\Categories\Services\CategoryService;
use App\Http\Controllers\BaseController;
use App\Menus\Http\Requests\UserIsOfEstablismentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Throwable;

class CategoryController extends BaseController
{
    public function __construct(
        private CategoryService $service
    )
    {}

    public function index(Request $request, string $establishmentId) : JsonResponse|AnonymousResourceCollection
    {
        $categories = $this->service->all(establishmentId : $establishmentId);

        return Category::collection($categories);
    }

    public function show(string $establishmentId, string $categoryId) : JsonResponse|Category
    {
        try {
            $category = $this->service->get(establishmentId: $establishmentId, categoryId: $categoryId);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => __('categories.category_not_found'),
            ], 404);
        }

        return new Category($category);
    }

    public function store(StoreCategoryRequest $request, string $establishmentId) : JsonResponse|Category
    {
        try {
            $category = $this->service->create(establishmentId: $establishmentId, data: $request->validated());
        } catch (CategorySameNameAlreadyExistsException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => __('categories.category_same_name_already_exists'),
            ], 400);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => __('categories.cold_not_create_category'),
            ], 400);
        }

        return new Category($category);
    }

    public function update(UpdateCategoryRequest $request, string $establishmentId, string $categoryId) : JsonResponse
    {
        try {
            $this->service->update(establishmentId: $establishmentId, categoryId: $categoryId, data: $request->validated());
        } catch (CategorySameNameAlreadyExistsException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => __('categories.category_same_name_already_exists'),
            ], 400);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => __('categories.cold_not_updated'),
            ], 400);
        }

        return response()->json([
            'message' => __('categories.category_updated_successfully'),
        ]);
    }

    public function destroy(UserIsOfEstablismentRequest $request, string $establishmentId, string $categoryId) : JsonResponse
    {
        try {
            $this->service->delete(establishmentId: $establishmentId, categoryId: $categoryId);
        } catch(RemoveItemsCategoryDeleteException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => __('categories.remove_items_delete_category'),
            ], 400);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => __('categories.cold_not_deleted'),
            ], 400);
        }

        return response()->json([
            'message' => __('categories.category_deleted_successfully'),
        ]);
    }
}
