<?php

namespace App\Establishments\Http\Requests;

class UpdateEstablishmentRequest extends UserIsAdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|min:2',
            'description' => 'string'
        ];
    }
}
