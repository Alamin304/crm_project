<?php
namespace App\Repositories;

use App\Models\WorkingHour;

class WorkingHourRepository
{
    public function create(array $input)
    {
        return WorkingHour::create($input);
    }

    public function update(array $input, $id)
    {
        $workingHour = WorkingHour::findOrFail($id);
        $workingHour->update($input);
        return $workingHour;
    }
}
