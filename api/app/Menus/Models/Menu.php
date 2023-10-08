<?php

namespace App\Menus\Models;

use App\Items\Models\Item;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends BaseModel
{
    use SoftDeletes, HasUuids;

    public $table = 'menus';

    public $fillable = [
        'establishment_id',
        'qr_code_image_path'
    ];

    public function items() : HasMany
    {
        return $this->hasMany(Item::class, 'menu_id', 'id');
    }
}
