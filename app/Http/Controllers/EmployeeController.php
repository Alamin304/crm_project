<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Queries\EmployeeDataTable;
use Illuminate\Http\Request;
use App\Repositories\EmployeeRepository;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Exception;
use App\Http\Requests\EmployeeRequest;
use App\Models\Designation;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Database\QueryException;
use App\Models\EmployeesDoc;
use Illuminate\Support\Facades\Response;
use App\Models\DocumentNextNumber;



class EmployeeController extends AppBaseController
{
    /**
     * @var EmployeeRepository
     */
    private $employeeRepository;
    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepository = $employeeRepo;
    }
    /**
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new EmployeeDataTable())->get($request->all()))->make(true);
        }
        $usersBranches = $this->getUsersBranches();

        return view('employees.index', compact('usersBranches'));
    }

    public function create(Request $request)
    {

        $departments = $this->employeeRepository->getDepartments();
        $subDepartments = $this->employeeRepository->getSubDepartment();
        $designations = $this->employeeRepository->getDesignation();
        $company = $this->employeeRepository->getCompanyName();
        $countries = $this->employeeRepository->getCountries();
        $shifts = $this->employeeRepository->getShifts();
        $nextNumber = DocumentNextNumber::getNextNumber('employee');
        $usersBranches = $this->getUsersBranches();

        return view('employees.create', compact(['departments', 'subDepartments', 'designations', 'company', 'countries', 'shifts', 'nextNumber', 'usersBranches']));
    }


    public function store(EmployeeRequest $request)
    {


        $input = $request->all();

        try {
            $employee = $this->employeeRepository->create($input);
            DocumentNextNumber::updateNumber('employee');
            activity()->causedBy(getLoggedInUser())
                ->performedOn($employee)
                ->useLog('Employee created.')
                ->log($employee->title . ' Designtion.');
            return $this->sendResponse($employee, __('messages.employees.saved_employee'));
        } catch (Throwable $e) {
            throw $e;
        }
    }
    public function destroy(Employee $employee)
    {

        $status =  $this->employeeRepository->delete_employee($employee);
        if ($status === true) {
            // Log the activity
            activity()->performedOn($employee)
                ->causedBy(getLoggedInUser())
                ->useLog('Employee deleted.')
                ->log($employee->name . ' Employee deleted.');
            return $this->sendSuccess('Employee deleted successfully.');
        } else {
            return  $this->sendError($status);
        }
    }

    public function view(Employee $employee)
    {
        $employee->load(['department', 'subDepartment', 'designation', 'countryEmployee', 'documents', 'shifts', 'branch']);

        return view('employees.view', compact('employee'));
    }

    public function export($status, $branch = null)
    {
        // Get employees based on the status
        $employees = $this->employeeRepository->getEmployeesByStatus($status, $branch);
        // Prepare CSV data
        $csvData = [];
        $csvData[] = ['SL', 'Branch', 'Iqama No', 'Name', 'Department', 'Designation', 'Employment Type', 'Absent Allowance Deduction', 'Status', 'Created At'];

        foreach ($employees as $index => $employee) {
            $csvData[] = [
                $index + 1, // Serial number
                $employee->branch?->name ?? '',
                $employee->iqama_no,
                $employee->name,
                $employee->department->name ?? 'N/A',
                $employee->designation->name ?? 'N/A',
                $employee->employment_type,
                $employee->absent_allowance_deduction ?? 0,
                $employee->status ? 'Active' : 'Inactive', // Status conversion
                \Carbon\Carbon::parse($employee->created_at)->format('d-m-Y') // Created At formatted
            ];
        }

        // Set the headers for the response
        $filename = 'employees_export_' . now()->format('Y-m-d_H-i') . '.csv';
        $handle = fopen('php://output', 'w');

        // Send the headers to the browser
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Write each row of the CSV to the output
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
        exit; // Terminate the script
    }

    public function edit(Employee $employee)
    {
        $employee->load('documents');
        $departments = $this->employeeRepository->getDepartments();
        $subDepartments = $this->employeeRepository->getSubDepartment();
        $designations = $this->employeeRepository->getDesignation();
        $company = $this->employeeRepository->getCompanyName();
        $countries = $this->employeeRepository->getCountries();
        $shifts = $this->employeeRepository->getShifts();
        $usersBranches = $this->getUsersBranches();
        return view('employees.edit', compact(['departments', 'subDepartments', 'designations', 'company', 'employee', 'countries', 'shifts', 'usersBranches']));
    }
    public function update(Employee $employee, UpdateEmployeeRequest $updateEmployeeRequest)
    {

        $input = $updateEmployeeRequest->all();
        $employee = $this->employeeRepository->update_employee($employee, $input);
        activity()->performedOn($employee)->causedBy(getLoggedInUser())
            ->useLog('Designation Updated')->log($employee->name . ' Employee updated.');
        return $this->sendSuccess(__('messages.employees.saved_employee'));
    }

    function file_delete($id)
    {

        $employee = $this->employeeRepository->delete_file($id);
        activity()->performedOn($employee)->causedBy(getLoggedInUser())
            ->useLog('File Deleted')->log(' Employee File deleted.');
        return $this->sendSuccess(__('messages.employees.delete_file'));
    }
}
