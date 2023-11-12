<?php

namespace App\Categories\Http\Requests;

use App\Menus\Http\Requests\UserIsOfEstablismentRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends UserIsOfEstablismentRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string'
        ];
    }
}
