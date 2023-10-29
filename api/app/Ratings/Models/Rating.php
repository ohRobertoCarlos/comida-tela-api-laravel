<?php

namespace App\Ratings\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends BaseModel
{
    use SoftDeletes, HasUuids;

    public $table = 'ratings';

    public $fillable = [
        'price_stars',
        'environment_stars',
        'service_stars',
        'products_stars',
        'establishment_id',
        'date_visit',
        'comment',
        'name',
        'phone_number',
        'birthday',
        'feedback',
    ];
}
