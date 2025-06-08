<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaintenance extends Model
{
    use HasFactory;
    protected $fillable = [
        'asset_id',
        'supplier_id',
        'maintenance_type',
        'title',
        'start_date',
        'completion_date',
        'warranty_improvement',
        'cost',
        'notes'
    ];

    protected $casts = [
        'warranty_improvement' => 'boolean',
        'start_date' => 'date',
        'completion_date' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
