<?php

namespace App\Items\Models;

use App\Models\BaseModel;
use Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends BaseModel
{
    use SoftDeletes, HasUuids, HasFactory;

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

    private $searchable = [
        'title' => 'like',
        'description' => 'like',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ItemFactory
    {
        return ItemFactory::new();
    }

    public function getSearchableFields() : array
    {
        return $this->searchable ?? [];
    }
}
