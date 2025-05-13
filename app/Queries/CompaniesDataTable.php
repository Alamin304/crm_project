<?php

namespace App\Queries;

use App\Models\Companie;
use Illuminate\Database\Eloquent\Builder;

class CompaniesDataTable
{
    public function get(): Builder
    {
        return Companie::query()->select('companies.*');
    }
}
