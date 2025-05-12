<?php

namespace App\Repositories;

use App\Models\JobPost;

class JobPostRepository
{
    public function create(array $data)
    {
        return JobPost::create($data);
    }

    public function update(array $data, $id)
    {
        $jobCategory = JobPost::findOrFail($id);
        $jobCategory->update($data);
        return $jobCategory;
    }
}

