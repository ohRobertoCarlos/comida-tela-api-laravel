<?php

namespace App\Establishments\Models;

use App\Models\BaseModel;
use Database\Factories\EstablishmentFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Establishment extends BaseModel
{
    use SoftDeletes, HasUuids, HasFactory;

    public $table = 'establishments';

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): EstablishmentFactory
    {
        return EstablishmentFactory::new();
    }

}
