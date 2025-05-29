<?php
namespace App\Queries;

use App\Models\RentalRequest;
use Illuminate\Database\Eloquent\Builder;

class RentalRequestDataTable
{
    public function get(): Builder
    {
        return RentalRequest::with('customer')->select('rental_requests.*');
    }
}
