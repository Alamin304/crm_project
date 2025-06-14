<?php

namespace App\Http\Controllers;

use App\Exports\WorkCentersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\WorkCenterRequest;
use App\Http\Requests\UpdateWorkCenterRequest;
use App\Models\WorkCenter;
use App\Queries\WorkCenterDataTable;
use App\Repositories\WorkCenterRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class WorkCenterController extends AppBaseController
{
    private $workCenterRepository;

    public function __construct(WorkCenterRepository $workCenterRepo)
    {
        $this->workCenterRepository = $workCenterRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new WorkCenterDataTable())->get())->make(true);
        }
        return view('work_centers.index');
    }

    public function create()
    {
        return view('work_centers.create');
    }

    public function store(WorkCenterRequest $request)
    {
        $input = $request->all();
        try {
            $workCenter = $this->workCenterRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($workCenter)
                ->useLog('Work Center created.')
                ->log($workCenter->name . ' Work Center Created');
            return $this->sendResponse($workCenter, __('messages.work_centers.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function show(WorkCenter $workCenter)
    {
        return view('work_centers.view', compact('workCenter'));
    }

    public function edit(WorkCenter $workCenter)
    {
        return view('work_centers.edit', compact('workCenter'));
    }

    public function update(WorkCenter $workCenter, UpdateWorkCenterRequest $request)
    {
        $input = $request->all();
        $workCenter = $this->workCenterRepository->update($input, $workCenter->id);
        activity()->performedOn($workCenter)->causedBy(getLoggedInUser())
            ->useLog('Work Center Updated')->log($workCenter->name . ' Work Center updated.');
        return $this->sendSuccess(__('messages.work_centers.saved'));
    }

    public function destroy(WorkCenter $workCenter)
    {
        try {
            $workCenter->delete();
            activity()->performedOn($workCenter)->causedBy(getLoggedInUser())
                ->useLog('Work Center deleted.')->log($workCenter->name . ' Work Center deleted.');
            return $this->sendSuccess(__('messages.work_centers.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'work_centers_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new WorkCentersExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $workCenters = WorkCenter::all();
            $pdf = PDF::loadView('work_centers.exports.work_centers_pdf', compact('workCenters'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new WorkCentersExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $workCenters = WorkCenter::orderBy('created_at', 'desc')->get();
            return view('work_centers.exports.work_centers_print', compact('workCenters'));
        }

        abort(404);
    }
}
