<?php

namespace App\Categories\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends BaseModel
{
    use SoftDeletes, HasUuids;

    public $table = 'categories';

    public $fillable = [
        'id',
        'name',
        'establishment_id'
    ];
}
