<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_name',
        'customer',
        'request_number',
        'inspected_property',
        'contract_amount',
        'property_price',
        'term',
        'start_date',
        'end_date',
        'bill_to',
        'ship_to',
        'status',
        'client_note',
        'admin_note'
    ];

    protected $casts = [
        'inspected_property' => 'boolean',
        'bill_to' => 'array',
        'ship_to' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'date_created' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Generate unique request number
    public static function generateRequestNumber()
    {
        $prefix = 'REQ-';
        $date = now()->format('Ymd');
        $latest = self::where('request_number', 'like', "{$prefix}{$date}%")->latest()->first();

        if ($latest) {
            $number = intval(substr($latest->request_number, -4)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . $date . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
