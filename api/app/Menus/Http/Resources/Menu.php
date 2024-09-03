<?php

namespace App\Menus\Http\Resources;

use App\Items\Http\Resources\Item;
use App\Shared\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Menu extends JsonResource
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
            'qr_code_image_path_url' => StorageService::getUrlPublicFile(publicFilePath: $this->qr_code_image_path),
            'establishment_id' => $this->establishment_id,
            'items' => Item::collection($this->whenLoaded('items')),
            'items_without_category' => Item::collection($this->whenLoaded('itemsWithoutCategory')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
