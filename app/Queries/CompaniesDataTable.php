<?php

namespace App\Queries;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;

class CompaniesDataTable
{
    public function get(): Builder
    {
        return Company::query()->select('companies.*');
    }
}
