<?php

namespace App\Repositories;

use App\Models\WorkCenter;
use Illuminate\Support\Facades\DB;

class WorkCenterRepository
{
    public function create(array $input)
    {
        return WorkCenter::create($input);
    }

    public function update(array $input, $id)
    {
        $workCenter = WorkCenter::findOrFail($id);
        $workCenter->update($input);
        return $workCenter;
    }
}
