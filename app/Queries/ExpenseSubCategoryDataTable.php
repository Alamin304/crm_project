<?php

namespace App\Queries;
use App\Models\SubDepartment;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ExpenseSubCategory;

/**
 * Class TagDataTable
 */
class ExpenseSubCategoryDataTable
{
    /**
     * @param  array  $input
     * @return ExpenseSubCategory
     */
    public function get($input = [])
    {
        /** @var ExpenseSubCategory $query */
        $query = ExpenseSubCategory::with('expenseCategory')->get();
        return $query;
    }
}
