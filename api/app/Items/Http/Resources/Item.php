<?php

namespace App\Items\Http\Resources;

use App\Shared\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
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
            'menu_id' => $this->menu_id,
            'likes' => $this->likes,
            'not_likes' => $this->not_likes,
            'title' => $this->title,
            'description' => $this->description,
            'cover_image_location_url' => StorageService::getUrlPublicFile(publicFilePath: $this->cover_image_location),
            'max_price' => $this->max_price,
            'min_price' => $this->min_price,
            'currency' => $this->currency,
            'portions' => $this->portions,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
