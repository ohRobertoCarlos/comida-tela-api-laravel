<?php

namespace App\Categories\Repositories;

use App\Categories\Models\Category;
use App\Repositories\BaseRepository as Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Category();
    }

    public function allFromEstablishment(string $establishmentId) : Collection
    {
        return $this->getModel()
            ->where('establishment_id', $establishmentId)
            ->orWhere('establishment_id', null)
            ->get();
    }

    public function categorySameNameExists(string $name, string $establishmentId, string|null $ignoredId = null) : bool
    {
        $query = $this->getModel()
        ->where(function (Builder $query) use ($name, $establishmentId) {
            $query->where('name', $name)
                ->where('establishment_id', $establishmentId)
                ->orWhere(function(Builder $q) use ($name) {
                    $q->where('name', $name)
                        ->whereNull('establishment_id');
                });
        });

        if ($ignoredId !== null) {
            $query = $query->where('id', '!=', $ignoredId);
        }

        return $query->exists();
    }
}
