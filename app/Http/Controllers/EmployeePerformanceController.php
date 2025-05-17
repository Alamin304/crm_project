<?php

// app/Http/Controllers/EmployeePerformanceController.php

namespace App\Http\Controllers;

use App\Models\EmployeePerformance;
use App\Http\Requests\EmployeePerformanceRequest;
use App\Http\Requests\UpdateEmployeePerformanceRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeePerformancesExport;
use App\Models\Employee;
use App\Queries\EmployeePerformanceDataTable;
use App\Repositories\EmployeePerformanceRepository;
use Throwable;
use Illuminate\Database\QueryException;

class EmployeePerformanceController extends AppBaseController
{
    private $employeePerformanceRepository;

    public function __construct(EmployeePerformanceRepository $employeePerformanceRepo)
    {
        $this->employeePerformanceRepository = $employeePerformanceRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new EmployeePerformanceDataTable())->get())
                ->addIndexColumn() // This adds the DT_RowIndex column
                ->editColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('d M Y, h:i A');
                })
                ->make(true);
        }

        return view('employee_performances.index');
    }

    public function create()
    {
        $employees = Employee::select('id', 'name')->get();
        return view('employee_performances.create', compact('employees'));
    }


    // public function store(EmployeePerformanceRequest $request)
    // {
    //     $input = $request->all();
    //     try {
    //         $employeePerformance = $this->employeePerformanceRepository->create($input);
    //         activity()->causedBy(getLoggedInUser())
    //             ->performedOn($employeePerformance)
    //             ->useLog('EmployeePerformance created.')
    //             ->log($employeePerformance->employee->name . ' Performance Created');
    //         return $this->sendResponse($employeePerformance, __('messages.employee_performances.saved'));
    //     } catch (Throwable $e) {
    //         throw $e;
    //     }
    // }

    public function store(EmployeePerformanceRequest $request)
    {
        $input = $request->all();

        try {
            $employeePerformance = $this->employeePerformanceRepository->create($input);

            activity()->causedBy(getLoggedInUser())
                ->performedOn($employeePerformance)
                ->useLog('EmployeePerformance created.')
                ->log($employeePerformance->employee->name . ' Performance Created');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'employeePerformance' => $employeePerformance,
                    'redirect' => route('employee_performances.index'),
                    'message' => __('messages.employee_performances.saved')
                ]);
            }

            return redirect()
                ->route('employee_performances.index')
                ->with('success', __('messages.employee_performances.saved'));
        } catch (Throwable $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }



    public function view(EmployeePerformance $employeePerformance)
    {
        return view('employee_performances.view', compact('employeePerformance'));
    }

    public function edit(EmployeePerformance $employeePerformance)
    {
        $employees = Employee::select('id', 'name')->get();
        return view('employee_performances.edit', compact('employeePerformance', 'employees'));
    }

    public function update(EmployeePerformance $employeePerformance, UpdateEmployeePerformanceRequest $request)
    {
        $input = $request->all();
        $this->employeePerformanceRepository->update($input, $employeePerformance->id);
        activity()->performedOn($employeePerformance)->causedBy(getLoggedInUser())
            ->useLog('EmployeePerformance Updated')->log($employeePerformance->employee->name . ' Performance updated.');
        return $this->sendSuccess(__('messages.employee_performances.saved'));
    }

    public function destroy(EmployeePerformance $employeePerformance)
    {
        try {
            $employeePerformance->delete();
            activity()->performedOn($employeePerformance)->causedBy(getLoggedInUser())
                ->useLog('EmployeePerformance deleted.')->log($employeePerformance->employee->name . ' Performance deleted.');
            return $this->sendSuccess(__('messages.employee_performances.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'employee_performances_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new EmployeePerformancesExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $employeePerformances = EmployeePerformance::all();
            $pdf = PDF::loadView('employee_performances.exports.employee_performances_pdf', compact('employeePerformances'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new EmployeePerformancesExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $employeePerformances = EmployeePerformance::with('employee')
                ->orderBy('created_at', 'desc')
                ->get();
            return view('employee_performances.exports.employee_performances_print', compact('employeePerformances'));
        }

        abort(404);
    }
}
