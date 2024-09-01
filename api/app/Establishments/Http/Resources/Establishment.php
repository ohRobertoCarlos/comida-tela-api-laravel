<?php

namespace App\Establishments\Http\Resources;

use App\Categories\Http\Resources\Category;
use App\Menus\Http\Resources\Menu;
use App\Profiles\Http\Resources\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Establishment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'menu_code' => $this->menu_code,
            'menu' => new Menu($this->whenLoaded('menu')),
            'profile' => new Profile($this->whenLoaded('profile')),
            'categories' => Category::collection($this->whenLoaded('categories')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
