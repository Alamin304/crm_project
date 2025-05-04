<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    public static $rules = [
        'name' => 'required',
        'purchase_date' => 'required',
        'manufacturer' => 'required',
        'warranty_end_date' => 'required'
    ];

    protected $guarded = [];
    /**
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'purchase_date' => 'datetime:Y-m-d',
        'manufacturer' => 'string',
        'warranty_end_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime', // Add created_at cast
        'updated_at' => 'datetime', // Add updated_at cast if needed
    ];
    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
