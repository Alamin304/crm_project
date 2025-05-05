<?php

namespace App\Queries;

use App\Models\WakeUpCall;

class WakeUpCallDataTable
{
    public function get()
    {
        return WakeUpCall::select(['id', 'customer_name', 'date', 'description', 'created_at']);
    }
}
