<?php

namespace App\Establishments\Http\Requests;

class CreateEstablishmentRequest extends UserIsAdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'string',
            'name' => 'required|string|min:2',
            'description' => 'string'
        ];
    }
}
