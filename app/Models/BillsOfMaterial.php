<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillsOfMaterial extends Model
{
    use HasFactory;

     protected $table = 'bills_of_materials';

    protected $fillable = [
        'BOM_code',
        'product',
        'product_variant',
        'quantity',
        'unit_of_measure',
        'routing',
        'bom_type',
        'manufacturing_readiness',
        'consumption',
        'description'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latestBOM = static::orderBy('id', 'desc')->first();
            $nextId = $latestBOM ? $latestBOM->id + 1 : 1;
            $model->BOM_code = 'BOM_' . str_pad($nextId, 9, '0', STR_PAD_LEFT);
        });
    }
}
