<?php

namespace App\Http\Controllers;

use App\Queries\SalaryAdvanceDataTable;
use Illuminate\Http\Request;
use App\Repositories\SalaryAdvanceRepository;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Exception;
use App\Http\Requests\SalaryAdvanceRequest;
use App\Models\SalaryAdvance;
use App\Http\Requests\UpdateSalaryAdvanceRequest;
use Illuminate\Database\QueryException;
use Laracasts\Flash\Flash;
use Throwable;
use App\Models\Bank;

class SalaryAdvanceController extends AppBaseController
{
    /**
     * @var SalaryAdvanceRepository
     */
    private $salaryAdvanceRepository;
    public function __construct(SalaryAdvanceRepository $salaryAdvanceRepo)
    {
        $this->salaryAdvanceRepository = $salaryAdvanceRepo;
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
            return DataTables::of((new SalaryAdvanceDataTable())->get($request->only(['group'])))->make(true);
        }

        return view('salary_advances.index');
    }

    public function create()
    {
        $employees = $this->salaryAdvanceRepository->getEmployee(); // Retrieves departments as key-value pairs
        $usersBranches = $this->getUsersBranches();
        $accounts = $this->salaryAdvanceRepository->getAccounts();
        return view('salary_advances.create', compact('employees', 'usersBranches', 'accounts'));
    }

    public function store(SalaryAdvanceRequest $request)
    {

        $input = $request->all();
        try {
            $salaryAdvance = $this->salaryAdvanceRepository->create($input);

            $salaryAdvance->load('employee');

            activity()->causedBy(getLoggedInUser())
                ->performedOn($salaryAdvance)
                ->useLog('Salary Advance created.')
                ->log($salaryAdvance->employee->name . ' Salary Advance Created.');
            Flash::success(__('messages.salary_advances.saved'));
            return $this->sendResponse($salaryAdvance->employee->name, __('messages.salary_advances.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }
    public function destroy(SalaryAdvance $salaryAdvance)
    {

        try {
            $salaryAdvance->delete();
            $salaryAdvance->load('employee');
            activity()->performedOn($salaryAdvance)->causedBy(getLoggedInUser())
                ->useLog('Salary deleted.')->log($salaryAdvance->employee->name . '  Salary Advance deleted.');
            return $this->sendSuccess(__('messages.salary_advances.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function edit(SalaryAdvance $salaryAdvance)
    {
        $employees = $this->salaryAdvanceRepository->getEmployee();
        $usersBranches = $this->getUsersBranches();
        $accounts = $this->salaryAdvanceRepository->getAccounts();
        return view('salary_advances.edit', compact(['employees', 'salaryAdvance', 'usersBranches', 'accounts']));
    }
    public function view(SalaryAdvance $salaryAdvance)
    {
        $salaryAdvance->load('employee', 'employee.designation');
        return view('salary_advances.view', compact(['salaryAdvance']));
    }
    public function update(SalaryAdvance $salaryAdvance, UpdateSalaryAdvanceRequest $updateSalaryAdvanceRequest)
    {

        $salaryAdvance->load('employee');

        $input = $updateSalaryAdvanceRequest->all();
        $input['status'] = $input['status'] ?? 0;
        $updateSalary = $this->salaryAdvanceRepository->update($input, $updateSalaryAdvanceRequest->id);
        activity()->performedOn($updateSalary)->causedBy(getLoggedInUser())
            ->useLog('Salary Advance Updated')->log($updateSalary->employee->name . ' Salary Advance updated.');
        Flash::success(__('messages.salary_advances.saved'));
        return $this->sendSuccess(__('messages.salary_advances.saved'));
    }
}
