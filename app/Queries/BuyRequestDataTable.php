<?php

namespace App\Queries;

use App\Models\BuyRequest;
use Illuminate\Database\Eloquent\Builder;

class BuyRequestDataTable
{
    public function get(): Builder
    {
        return BuyRequest::with('customer')->select('buy_requests.*');
    }
}
