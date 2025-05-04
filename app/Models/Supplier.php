<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Country;
use App\Models\SupplierToGroup;

class Supplier extends Model
{
    use HasFactory;

    public static $rules = [
        'company_name' => 'required|unique:suppliers,company_name',
    ];

    /**
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * @var array
     */
    protected $fillable = [
        'company_name',
        'vat_number',
        'phone',
        'website',
        'currency',
        'country',
        'default_language',
        'street',
        'city',
        'state',
        'zip'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'company_name' => 'string',
        'vat_number' => 'string',
        'phone' => 'string',
        'website' => 'string',
        'currency' => 'integer',
        'country' => 'integer',
        'default_language' => 'string',
        'street' => 'string',
        'city' => 'string',
        'state' => 'string',
        'zip' => 'string',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }

    public function groups()
    {
        return $this->belongsTo(SupplierToGroup::class, 'id', 'supplier_id');
    }
    public function supplierGroups()
    {
        return $this->hasMany(SupplierToGroup::class, 'supplier_id');
    }
}
