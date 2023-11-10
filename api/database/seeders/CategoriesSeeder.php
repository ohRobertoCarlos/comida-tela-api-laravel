<?php

namespace Database\Seeders;

use App\Categories\Enums\Category;
use App\Categories\Repositories\CategoryRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(CategoryRepository $categoryRepository): void
    {
        $category = $categoryRepository->findById(Category::Drinks->value);
        if (empty($category)) {
            $categoryRepository->create([
                'id' => Category::Drinks->value,
                'name' => Category::Drinks->name
            ]);
        }

        $category = $categoryRepository->findById(Category::Combos->value);
        if (empty($category)) {
            $categoryRepository->create([
                'id' => Category::Combos->value,
                'name' => Category::Combos->name
            ]);
        }

        $category = $categoryRepository->findById(Category::Highlights->value);
        if (empty($category)) {
            $categoryRepository->create([
                'id' => Category::Highlights->value,
                'name' => Category::Highlights->name
            ]);
        }
    }
}
