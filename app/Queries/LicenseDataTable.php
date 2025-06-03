<?php

namespace App\Queries;

use App\Models\License;
use Illuminate\Database\Eloquent\Builder;

class LicenseDataTable
{
    public function get(): Builder
    {
        return License::query()->select('licenses.*');
    }
}