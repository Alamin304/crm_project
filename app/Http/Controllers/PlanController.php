<?php

namespace App\Http\Controllers;

use App\Exports\PlansExport;
use App\Http\Requests\PlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Imports\PlansImport;
use App\Models\Plan;
use App\Queries\PlanDataTable;
use App\Repositories\PlanRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PlanController extends AppBaseController
{
    private $planRepository;

    public function __construct(PlanRepository $repo)
    {
        $this->planRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dataTable = new PlanDataTable();
            return $dataTable->dataTable($dataTable->get())->make(true);
        }

        return view('plans.index');
    }

    public function create()
    {
        return view('plans.create');
    }

    public function store(PlanRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('attachment')) {
            $input['attachment'] = $request->file('attachment');
        }

        $plan = $this->planRepository->create($input);

        return $this->sendResponse($plan, 'Plan saved successfully.');
    }

    public function show(Plan $plan)
    {
        return view('plans.view', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        return view('plans.edit', compact('plan'));
    }

    public function update(Plan $plan, UpdatePlanRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('attachment')) {
            $input['attachment'] = $request->file('attachment');
        }

        $this->planRepository->update($input, $plan->id);

        return $this->sendSuccess('Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        $this->planRepository->delete($plan->id);

        return $this->sendSuccess('Plan deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'plans_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new PlansExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $plans = Plan::all();
            $pdf = Pdf::loadView('plans.exports.plans_pdf', compact('plans'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new PlansExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $plans = Plan::orderBy('id')->get();
            return view('plans.exports.plans_print', compact('plans'));
        }

        abort(404);
    }
}
