<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function getSearchableFields() : array
    {
        return $this->searchable ?? [];
    }
}
