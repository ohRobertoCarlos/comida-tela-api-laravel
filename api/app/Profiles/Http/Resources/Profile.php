<?php

namespace App\Profiles\Http\Resources;

use App\Shared\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
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
            'establishment_id' => $this->establishment_id,
            'facebook_link' => $this->facebook_link,
            'instagram_link' => $this->instagram_link,
            'whatsapp' => $this->whatsapp,
            'opening_hours' => $this->opening_hours,
            'payment_methods' => $this->payment_methods,
            'localization' => $this->localization,
            'address' => $this->address,
            'image_cover_profile_location_url' => $this->image_cover_profile_location ? StorageService::getUrlPublicFile(publicFilePath: $this->image_cover_profile_location) : '',
            'image_cover_background_profile_url' => $this->image_cover_background_profile_location ? StorageService::getUrlPublicFile(publicFilePath: $this->image_cover_background_profile_location) : '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
