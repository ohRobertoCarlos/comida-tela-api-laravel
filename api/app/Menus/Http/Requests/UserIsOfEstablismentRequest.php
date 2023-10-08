<?php

namespace App\Menus\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserIsOfEstablismentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->establishment_id === $this->route('establishment_id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
