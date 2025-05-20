<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Exports\CheckInExport;
use Illuminate\Http\Request;
use App\Http\Requests\CheckInRequest;
use App\Http\Requests\UpdateCheckInRequest;
use App\Imports\CheckInImport;
use App\Queries\CheckInDataTable;
use App\Repositories\CheckInRepository;
use Throwable;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
    public function downloadSampleCsv(): StreamedResponse
{
    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=check_ins_sample.csv",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $columns = [
        'booking_number',
        'check_in',
        'check_out',
        'arrival_from',
        'booking_type',
        'booking_reference',
        'booking_reference_no',
        'visit_purpose',
        'remarks',
        'room_type',
        'room_no',
        'adults',
        'children',
        'booking_status'
    ];
    
    $rows = [
        [
            'BK-001',
            '2023-06-15 14:00',
            '2023-06-20 12:00',
            'New York',
            'Online',
            'Website',
            'REF12345',
            'Business',
            'Early check-in requested',
            'Deluxe',
            '101',
            '2',
            '1',
            '1'
        ],
        [
            'BK-002',
            '2023-06-18 16:00',
            '2023-06-22 11:00',
            'London',
            'Travel Agent',
            'AgentXYZ',
            'REF67890',
            'Vacation',
            'Honeymoon package',
            'Suite',
            '205',
            '2',
            '0',
            '1'
        ],
    ];

    $callback = function () use ($columns, $rows) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($rows as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,txt|max:2048',
    ]);

    try {
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $headers = fgetcsv($file);

        $expectedHeaders = [
            'booking_number',
            'check_in',
            'check_out',
            'arrival_from',
            'booking_type',
            'booking_reference',
            'booking_reference_no',
            'visit_purpose',
            'remarks',
            'room_type',
            'room_no',
            'adults',
            'children',
            'booking_status'
        ];

        if (array_diff($expectedHeaders, array_map('strtolower', $headers))) {
            fclose($file);
            return redirect()->back()->with('error', 'Invalid file format. Please download the sample CSV for the correct format.');
        }

        fclose($file);

        Excel::import($import = new CheckInImport, $request->file('file'));

        if (!empty($import->failures())) {
            return redirect()->back()->with([
                'failures' => $import->failures(),
            ]);
        }

        return redirect()->route('check_ins.index')->with('success', 'Check-ins imported successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
    }
}
}
