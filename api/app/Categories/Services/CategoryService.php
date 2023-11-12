<?php

namespace App\Categories\Services;

use App\Categories\Events\CategoryWasCreated;
use App\Categories\Events\CategoryWasDeleted;
use App\Categories\Events\CategoryWasUpdated;
use App\Categories\Exceptions\CategorySameNameAlreadyExistsException;
use App\Categories\Exceptions\RemoveItemsCategoryDeleteException;
use App\Categories\Repositories\CategoryRepository;
use App\Establishments\Services\EstablishmentService;
use App\Models\BaseModel;
use Exception;
use Illuminate\Support\Collection;

class CategoryService
{
    public function __construct(
        private CategoryRepository $repository,
        private EstablishmentService $establishmentService
    )
    {}

    public function get(string $establishmentId, string $categoryId) : BaseModel|null
    {
        $category = $this->repository->findById(id: $categoryId);
        if (
            empty($category) ||
            ($category->establishment_id !== null && $category->establishment_id !== $establishmentId)
        ) {
            throw new Exception('Category not found.');
        }

        return $category;
    }

    public function all(string $establishmentId) : Collection
    {
        return $this->repository->allFromEstablishment(establishmentId: $establishmentId);
    }

    public function create(string $establishmentId, array $data) : BaseModel|null
    {
        $establishment = $this->establishmentService->get($establishmentId);
        if (empty($establishment)) {
            throw new Exception('Establishment not found.');
        }

        if (
            $this->categorySameNameExists($data['name'], $establishment->id)
        ) {
            throw new CategorySameNameAlreadyExistsException('Category same name already exists.');
        }

        $data['establishment_id'] = $establishmentId;

        $category = $this->repository->create($data);

        event(new CategoryWasCreated($category));

        return $category;
    }

    public function categorySameNameExists(string $name, string $establishmentId, string|null $ignoredId = null) : bool
    {
        return $this->repository->categorySameNameExists(name: $name, establishmentId: $establishmentId, ignoredId: $ignoredId);
    }

    public function update(string $establishmentId, string $categoryId, array $data) : bool
    {
        $category = $this->get($establishmentId, $categoryId);

        if (empty($category)) {
            throw new Exception('Category not found.');
        }

        if (
            $this->categorySameNameExists($data['name'], $establishmentId, $categoryId)
        ) {
            throw new CategorySameNameAlreadyExistsException('Category same name already exists.');
        }

        $category->update($data);
        $category = $category->fresh();

        event(new CategoryWasUpdated($category));

        return true;
    }

    public function delete(string $establishmentId, string $categoryId) : bool
    {
        $category = $this->get($establishmentId, $categoryId);

        if ($category->establishment_id === null) {
            throw new Exception('It is not possible to delete a default category.');
        }

        if (
            $category->items()->count() > 0
        ) {
            throw new RemoveItemsCategoryDeleteException('Remove items related to the category to delete it.');
        }

        $category->delete();

        event(new CategoryWasDeleted($category));

        return true;
    }
}
