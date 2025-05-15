<?php

namespace App\Repositories;

use App\Models\EmployeePerformance;

class EmployeePerformanceRepository
{
    public function create(array $data)
    {
        return EmployeePerformance::create($data);
    }

    public function update(array $data, $id)
    {
        $employeePerformance = EmployeePerformance::findOrFail($id);
        $employeePerformance->update($data);
        return $employeePerformance;
    }

    public function delete($id)
    {
        $employeePerformance = EmployeePerformance::findOrFail($id);
        return $employeePerformance->delete();
    }

    public function getAll()
    {
        return EmployeePerformance::all();
    }
}
