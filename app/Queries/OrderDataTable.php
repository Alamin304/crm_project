<?php

namespace App\Queries;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class OrderDataTable
{
    public function get(): Builder
    {
        return Order::query()->select('orders.*');
    }
}
