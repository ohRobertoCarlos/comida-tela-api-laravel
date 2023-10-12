<?php

namespace Database\Factories;

use App\Items\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_id' => fake()->uuid(),
            'title' => fake()->title(),
            'likes' => 0,
            'not_likes' => 0,
            'description' => fake()->text(),
            'cover_image_location' => fake()->imageUrl(),
            'max_price' => null,
            'min_price' => 16.99,
            'currency' => 'BRL',
            'portions' => 1,
        ];
    }
}
