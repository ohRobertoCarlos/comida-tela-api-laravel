<?php

namespace App\Establishments\Http\Resources;

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
            'menu' => new Menu($this->whenLoaded('menu')),
            'profile' => new Profile($this->whenLoaded('profile')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
