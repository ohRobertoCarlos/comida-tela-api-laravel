<?php

namespace App\Ratings\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Rating extends JsonResource
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
            'price_stars' => $this->price_stars,
            'environment_stars' => $this->environment_stars,
            'service_stars' => $this->service_stars,
            'products_stars' => $this->products_stars,
            'establishment_id' => $this->establishment_id,
            'date_visit' => $this->date_visit,
            'comment' => $this->comment,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'birthday' => $this->birthday,
            'feedback' => $this->feedback
        ];
    }
}
