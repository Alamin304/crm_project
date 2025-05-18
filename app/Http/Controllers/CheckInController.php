<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Exports\CheckInExport;
use Illuminate\Http\Request;
use App\Http\Requests\CheckInRequest;
use App\Http\Requests\UpdateCheckInRequest;
use App\Queries\CheckInDataTable;
use App\Repositories\CheckInRepository;
use Throwable;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;


class CheckInController extends AppBaseController
{
    private $checkInRepository;

    public function __construct(CheckInRepository $checkInRepository)
    {
        $this->checkInRepository = $checkInRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new CheckInDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('check_ins.index');
    }

    public function create()
    {
        return view('check_ins.create');
    }

    public function store(CheckInRequest $request)
    {
        try {
            $checkin = $this->checkInRepository->create($request->all());
            activity()->causedBy(getLoggedInUser())
                ->performedOn($checkin)
                ->log("Check In Created: {$checkin->booking_number}");
            return $this->sendResponse($checkin, 'Check In created successfully.');
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(CheckIn $checkIn)
    {
        return view('check_ins.view', compact('checkIn'));
    }

    public function edit(CheckIn $checkIn)
    {
        return view('check_ins.edit', compact('checkIn'));
    }

    public function update(UpdateCheckInRequest $request, CheckIn $checkIn)
    {
        $updated = $this->checkInRepository->update($request->all(), $checkIn->id);
        activity()->causedBy(getLoggedInUser())
            ->performedOn($checkIn)
            ->log("Check In Updated: {$checkIn->booking_number}");
        return $this->sendSuccess('Check In updated successfully.');
    }

    public function destroy(CheckIn $checkIn)
    {
        $checkIn->delete();
        activity()->causedBy(getLoggedInUser())
            ->performedOn($checkIn)
            ->log("Check In Deleted: {$checkIn->booking_number}");
        return $this->sendSuccess('Check In deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'check_in_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new CheckInExport, $fileName, \Maatwebsite\Excel\Excel::CSV);
        }

        if ($format === 'pdf') {
            $checkIns = CheckIn::all();
            $pdf = Pdf::loadView('check_ins.exports.check_in_pdf', compact('checkIns'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new CheckInExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $checkIns = CheckIn::orderBy('created_at', 'desc')->get();
            return view('check_ins.exports.check_in_print', compact('checkIns'));
        }

        abort(404);
    }
}
