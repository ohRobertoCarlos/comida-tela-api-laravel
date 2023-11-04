<?php

namespace App\Items\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
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
            'date_visit' => 'required|date',
            'comment' => 'required|string',
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'birthday' => 'required|date',
        ];
    }
}
