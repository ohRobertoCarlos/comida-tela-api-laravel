<?php

namespace App\Items\Http\Requests;

use App\Menus\Http\Requests\UserIsOfEstablismentRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends UserIsOfEstablismentRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string|min:3',
            'description' => 'string|min:3',
            'max_price' => 'nullable|decimal:2',
            'min_price' => 'decimal:2',
            'currency' => 'string',
            'portions' => 'integer',
            'cover_image' => 'image'
        ];
    }
}
