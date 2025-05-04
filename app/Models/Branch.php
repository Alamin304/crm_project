<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    // Validation rules for the model
    public static $rules = [
        'company_name' => 'required',
        'name' => 'required|unique:branches,name',
        // Add other validation rules as needed
    ];

    /**
     * @var string
     */
    protected $table = 'branches';

    /**
     * @var array
     */
    protected $fillable = [
        'company_name',
        'name',
        'website',
        'vat_number',
        'currency_id',
        'city',
        'state',
        'country_id',
        'zip_code',
        'phone',
        'address',
        'bank_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'company_name' => 'string',
        'name' => 'string',
        'website' => 'string',
        'vat_number' => 'string',
        'currency_id' => 'integer',
        'city' => 'string',
        'state' => 'string',
        'country_id' => 'integer',
        'zip_code' => 'string',
        'phone' => 'string',
        'address' => 'string',
    ];

    /**
     * Get the country that owns the branch.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * Get the currency that belongs to the branch.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
    public function UsersBranches()
    {
        return $this->hasMany(UsersBranch::class, 'branch_id', 'id');
    }


    public function accounts()
    {
        return $this->hasMany(Account::class, 'branch_id');
    }
}
