<?php

namespace App\Queries;

use App\Models\PreAlert;
use Illuminate\Database\Eloquent\Builder;

class PreAlertDataTable
{
    public function get(): Builder
    {
        return PreAlert::query()->select('pre_alerts.*');
    }
}
