<?php

namespace App\Categories\Http\Controllers;

use App\Categories\Exceptions\CategorySameNameAlreadyExistsException;
use App\Categories\Http\Requests\StoreCategoryRequest;
use App\Categories\Http\Resources\Category;
use App\Categories\Services\CategoryService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

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

    public function show()
    {

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
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => __('categories.cold_not_create_category'),
            ], 400);
        }

        return new Category($category);
    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
