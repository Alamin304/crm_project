<?php

namespace App\Queries;

use App\Models\OrgChart;
use Illuminate\Database\Eloquent\Builder;

class OrgChartDataTable
{
    public function get(): Builder
    {
        return OrgChart::query()->select('org_charts.*');
    }

}

