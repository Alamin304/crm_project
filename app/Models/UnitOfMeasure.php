<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    use HasFactory;
     protected $fillable = ['name', 'type', 'category_id', 'rounding_precision', 'is_active'];

      public function category()
    {
        return $this->belongsTo(UnitOfMeasureCategory::class, 'category_id');
    }
}
