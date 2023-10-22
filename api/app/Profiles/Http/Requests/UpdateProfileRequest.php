<?php

namespace App\Profiles\Http\Requests;

use App\Menus\Http\Requests\UserIsOfEstablismentRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends UserIsOfEstablismentRequest
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
            'facebook_link' => 'string',
            'instagram_link' => 'string',
            'whatsapp' => 'string',
            'opening_hours' => 'json',
            'payment_methods' => 'json',
            'localization' => 'string',
            'address' => 'string',
            'image_cover_profile' => 'image'
        ];
    }
}
