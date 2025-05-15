<?php

namespace App\Queries;

use App\Models\EmployeePerformance;
use Illuminate\Database\Eloquent\Builder;

class EmployeePerformanceDataTable
{
     public function get(): Builder
    {
        return EmployeePerformance::query()
            ->select('employee_performances.*', 'employees.name as employee_name')
            ->leftJoin('employees', 'employees.id', '=', 'employee_performances.employee_id');
    }
}
