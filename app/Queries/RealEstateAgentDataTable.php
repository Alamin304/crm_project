<?php

namespace App\Queries;

use App\Models\RealEstateAgent;
use Illuminate\Database\Eloquent\Builder;

class RealEstateAgentDataTable
{
    public function get(): Builder
    {
        return RealEstateAgent::query()->select('real_estate_agents.*');
    }
}
