<?php

namespace App\Items\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends BaseModel
{
    use SoftDeletes, HasUuids;

    public $table = 'items';

    protected $fillable = [
        'id',
        'menu_id',
        'title',
        'likes',
        'not_likes',
        'description',
        'cover_image_location',
        'max_price',
        'min_price',
        'currency',
        'portions',
    ];
}
