<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Project;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\SalaryGenerate;


/**
 * Class ProductRepository
 *
 * @version October 12, 2021, 10:50 am UTC
 */
class ManageAttendanceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',

    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Product::class;
    }

    /**
     * @return mixed
     */
    public function getSyncListForItem()
    {
        $taxes = [];

        $taxRates = TaxRate::get();

        foreach ($taxRates as $tax) {
            $taxes[$tax->id] = $tax->tax_rate . '%';
        }

        $data['taxes'] = $taxes;
        $data['itemGroups'] = ProductGroup::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        return $data;
    }

    public function getCustomers()
    {
        return  Customer::pluck('company_name', 'id'); // Retrieves departments as key-value pairs
    }
    public function getProjects()
    {
        return  Project::get(); // Retrieves departments as key-value pairs
    }
    public function getAllEmployees()
    {
        return Employee::select('id', 'name', 'iqama_no')->get();
    }
    public function allEmployees()
    {
        return Employee::all();
    }
    public function getDepartments()
    {
        return Department::pluck('name', 'id')->toArray();
    }

    public function getDesignation()
    {
        return Designation::select(['name', 'id', 'sub_department_id', 'department_id'])->get();
    }
    public function salaryStatus($month, $branch_id)
    {
        // Get the salary records where the salary_month matches the provided month
        $salaryRecords = SalaryGenerate::where('salary_month', $month)
            ->where('branch_id', $branch_id)
            ->get();
        // Check if any records are found
        if ($salaryRecords->isNotEmpty()) {
            // If records are found, return true
            return true;
        }

        // If no matching records found, return false
        return false;
    }
}
