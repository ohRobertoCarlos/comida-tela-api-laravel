<?php

namespace App\Categories\Repositories;

use App\Categories\Models\Category;
use App\Repositories\BaseRepository as Repository;

class CategoryRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Category();
    }
}
