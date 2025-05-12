<?php

namespace App\Queries;

use App\Models\JobPost;
use Illuminate\Database\Eloquent\Builder;

class JobPostDataTable
{
    public function get(): Builder
    {
        return JobPost::with('category')->select('job_posts.*');
    }
}

