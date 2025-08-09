<?php

namespace App\Ratings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'price_stars' => 'integer|max:5',
            'environment_stars' => 'integer|max:5',
            'service_stars' => 'integer|max:5',
            'products_stars' => 'integer|max:5',
            'date_visit' => 'date',
            'comment' => 'nullable|string',
            'name' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'birthday' => 'nullable|date',
            'feedback' => 'nullable|in:positive,negative'
        ];
    }
}
