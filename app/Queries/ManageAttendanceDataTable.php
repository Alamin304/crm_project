<?php

namespace App\Queries;


use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ProjectMember;
use App\Models\NewAttendance;

/**
 * Class TagDataTable
 */
class ManageAttendanceDataTable
{
    /**
     * @param  array  $input
     * @return Employee
     */
    public function get($input = [])
    {
        /** @var Employee $query */
        $query = Employee::with([
            'employeeRates' => function ($q) use ($input) {
                // Ensure all three parameters are present
                if (isset($input['customer_id'], $input['project_id'], $input['month'])) {
                    $q->where('customer_id', $input['customer_id'])
                        ->where('project_id', $input['project_id'])
                        ->whereBetween('month', [
                            $input['month'] . '-01', // Start of the month
                            date('Y-m-t', strtotime($input['month'] . '-01')) // End of the month
                        ]);
                }
            },
            'projectMember',
            'projectMember.project',
            'projectMember.project.customer',
            'department',
            'designation',
            'attendance',
            'branch'

        ]);
        if (isset($input['branch_id'])) {
            $query->where('branch_id', $input['branch_id']);
        }



        // ->whereHas('projectMember') // This ensures only employees with project members are fetched


        // if (isset($input['project_id']) && $input['project_id']) {
        //     $query->whereHas('projectMember', function ($q) use ($input) {
        //         $q->where('owner_id', $input['project_id']);
        //     });
        // }

        // if (isset($input['customer_id']) && $input['customer_id']) {
        //     $query->whereHas('projectMember.project.customer', function ($q) use ($input) {
        //         $q->where('id', $input['customer_id']);
        //     });
        // }

        if (isset($input['department_id']) && $input['department_id']) {
            $query->whereHas('department', function ($q) use ($input) {
                $q->where('id', $input['department_id']);
            });
        }

        if (isset($input['desgnation_id']) && $input['desgnation_id']) {
            $query->whereHas('designation', function ($q) use ($input) {
                $q->where('id', $input['desgnation_id']);
            });
        }
        if (isset($input['employee_id']) && $input['employee_id']) {
            $query->where('id', $input['employee_id']);
        }

        // Filter by month and ensure attendance records are included
        if (isset($input['month']) && $input['month']) {
            $query->with('attendance', function ($q) use ($input) {
                $q->whereYear('date', date('Y', strtotime($input['month'])))
                    ->whereMonth('date', date('m', strtotime($input['month'])));
            });
        }
        if (isset($input['iqama_no']) && $input['iqama_no']) {
            $query->where('iqama_no', 'LIKE', $input['iqama_no'] . '%');
        }



        $query->check = "Test";

        $query->get();
        return $query;
    }
}
