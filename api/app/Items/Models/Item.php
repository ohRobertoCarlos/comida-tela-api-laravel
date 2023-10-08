<?php

namespace App\Items\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends BaseModel
{
    use SoftDeletes, HasUuids;

    public $table = 'items';
}
