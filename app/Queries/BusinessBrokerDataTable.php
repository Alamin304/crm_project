<?php

namespace App\Queries;

use App\Models\BusinessBroker;
use Illuminate\Database\Eloquent\Builder;

class BusinessBrokerDataTable
{
    public function get(): Builder
    {
        return BusinessBroker::query()->select('business_brokers.*');
    }
}
