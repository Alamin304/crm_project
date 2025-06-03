<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'asset_name',
        'model',
        'status',
        'supplier',
        'purchase_date',
        'order_number',
        'purchase_cost',
        'location',
        'warranty_months',
        'requestable',
        'for_sale',
        'selling_price',
        'for_rent',
        'rental_price',
        'minimum_renting_days',
        'rental_unit',
        'description'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'requestable' => 'boolean',
        'for_sale' => 'boolean',
        'for_rent' => 'boolean',
    ];

    public static function statusOptions()
    {
        return [
            'ready' => 'Ready',
            'pending' => 'Pending',
            'undeployable' => 'Undeployable',
            'archived' => 'Archived',
            'operational' => 'Operational',
            'non-operational' => 'Non-Operational',
            'repairing' => 'Repairing'
        ];
    }

    public static function modelOptions()
    {
        return [
            'Model A' => 'Model A',
            'Model B' => 'Model B',
            'Model C' => 'Model C',
            'Model D' => 'Model D',
        ];
    }

    public static function supplierOptions()
    {
        return [
            'Supplier X' => 'Supplier X',
            'Supplier Y' => 'Supplier Y',
            'Supplier Z' => 'Supplier Z',
        ];
    }

    public static function locationOptions()
    {
        return [
            'Location 1' => 'Location 1',
            'Location 2' => 'Location 2',
            'Location 3' => 'Location 3',
        ];
    }

    public static function rentalUnitOptions()
    {
        return [
            'day' => 'Per Day',
            'week' => 'Per Week',
            'month' => 'Per Month',
            'year' => 'Per Year',
        ];
    }
}
