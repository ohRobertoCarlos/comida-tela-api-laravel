<?php

namespace App\Establishments\Models;

use App\Menus\Models\Menu;
use App\Models\BaseModel;
use App\Profiles\Models\Profile;
use Database\Factories\EstablishmentFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Establishment extends BaseModel
{
    use SoftDeletes, HasUuids, HasFactory;

    public $table = 'establishments';

    protected $fillable = [
        'id',
        'name',
        'description',
        'menu_code',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): EstablishmentFactory
    {
        return EstablishmentFactory::new();
    }

    public function menu() : HasOne
    {
        return $this->hasOne(Menu::class);
    }

    public function profile() : HasOne
    {
        return $this->hasOne(Profile::class);
    }

}
