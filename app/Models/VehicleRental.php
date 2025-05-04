<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleRental extends Model
{
    use HasFactory;

    protected $table = 'vehicle_rentals';

    protected $fillable = [
        'rental_number',
        'plate_number',
        'name',
        'type',
        'amount',
        'agreement_date',
        'expiry_date',
        'notification_date',
        'description',
        'notification_days',
        'branch_id',
        'account_id',
        'paid_amount',
        'agreement_type'
    ];

    // Vehicle types as an array
    public static function getVehicleTypes()
    {
        return ['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Yearly' => 'Yearly', 'Twicly' => 'Twicly', 'Quarterly' => 'Quarterly', 'Half Year' => 'Half Year'];
    }

    // Validation rules
    public static function rules($id = null)
    {
        return [
            'rental_number'    => 'required|string|max:50|unique:vehicle_rentals,rental_number,' . $id,
            'plate_number'     => 'nullable|string|max:50|unique:vehicle_rentals,plate_number,' . $id,
            'name'             => 'required|string|max:100',
            'type'             => 'nullable|in:Daily,Weekly,Monthly,Yearly,Twicly,Quarterly,Half Year',
            'amount'           => 'required|numeric|min:0',
            'agreement_date'   => 'nullable|date',
            'expiry_date'      => 'required|date|after_or_equal:agreement_date',
            'notification_date' => 'nullable|date',
            'description'      => 'nullable',
            'agreement_type' => 'nullable|in:One-time,Installment',
        ];
    }
}
