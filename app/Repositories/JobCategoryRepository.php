<?php

namespace App\Repositories;

use App\Models\JobCategory;

class JobCategoryRepository
{
    public function create(array $data)
    {
        return JobCategory::create($data);
    }

    public function update(array $data, $id)
    {
        $jobCategory = JobCategory::findOrFail($id);
        $jobCategory->update($data);
        return $jobCategory;
    }
}

