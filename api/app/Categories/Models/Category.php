<?php

namespace App\Categories\Models;

use App\Items\Models\Item;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function items() : BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'items_categories', 'category_id', 'item_id');
    }
}
