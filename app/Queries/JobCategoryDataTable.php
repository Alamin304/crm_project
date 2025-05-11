<?php

namespace App\Queries;

use App\Models\JobCategory;
use App\Models\Position;
use Illuminate\Database\Eloquent\Builder;

class JobCategoryDataTable
{
    public function get(): Builder
    {
        return JobCategory::query()->select('job_categories.*');
    }
}
