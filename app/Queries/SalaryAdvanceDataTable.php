<?php

namespace App\Queries;
use App\Models\SalaryAdvance;

/**
 * Class TagDataTable
 */
class SalaryAdvanceDataTable
{
    /**
     * @param  array  $input
     * @return SalaryAdvance
     */
    public function get($input = [])
    {
        /** @var SalaryAdvance $query */
        $query = SalaryAdvance::with(['employee', 'permittedBy','employee.branch'])->orderBy('created_at','desc')->get();
        return $query;
    }
}
