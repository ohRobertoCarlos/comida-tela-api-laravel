<?php

namespace App\Profiles\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends BaseModel
{
    use SoftDeletes, HasUuids;

    public $table = 'profiles';

    public $fillable = [
        'establishment_id',
        'facebook_link',
        'instagram_link',
        'whatsapp',
        'opening_hours',
        'payment_methods',
        'localization',
        'address',
        'image_cover_profile_location'
    ];
}
