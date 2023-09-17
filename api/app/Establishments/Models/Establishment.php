<?php

namespace App\Establishments\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Establishment extends BaseModel
{
    use SoftDeletes, HasUuids;

    public $table = 'establishments';

    protected $fillable = [
        'name',
        'description'
    ];

}