<?php

namespace App\Http\Controllers;

use App\Models\BookingList;
use App\Exports\BookingListsExport;
use Illuminate\Http\Request;
use App\Queries\BookingListDataTable;
use App\Http\Requests\BookingListRequest;
use App\Http\Requests\UpdateBookingListRequest;
use App\Imports\BookingListImport;
use App\Repositories\BookingListRepository;
use Throwable;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class BookingListController extends AppBaseController
{
    private $bookingListRepository;

    public function __construct(BookingListRepository $bookingListRepository)
    {
        $this->bookingListRepository = $bookingListRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new BookingListDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('booking_lists.index');
    }

    public function create()
    {
        return view('booking_lists.create');
    }

    public function store(BookingListRequest $request)
    {
        try {
            $booking = $this->bookingListRepository->create($request->all());
            activity()->causedBy(getLoggedInUser())
                ->performedOn($booking)
                ->log("Booking Created: {$booking->booking_number}");
            return $this->sendResponse($booking, 'Booking created successfully.');
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(BookingList $bookingList)
    {
        return view('booking_lists.view', compact('bookingList'));
    }

    public function edit(BookingList $bookingList)
    {
        return view('booking_lists.edit', compact('bookingList'));
    }

    public function update(UpdateBookingListRequest $request, BookingList $bookingList)
    {
        $updated = $this->bookingListRepository->update($request->all(), $bookingList->id);
        activity()->causedBy(getLoggedInUser())
            ->performedOn($bookingList)
            ->log("Booking Updated: {$bookingList->booking_number}");
        return $this->sendSuccess('Booking updated successfully.');
    }

    public function destroy(BookingList $bookingList)
    {
        $bookingList->delete();
        activity()->causedBy(getLoggedInUser())
            ->performedOn($bookingList)
            ->log("Booking Deleted: {$bookingList->booking_number}");
        return $this->sendSuccess('Booking deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'booking_lists_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new BookingListsExport, $fileName, \Maatwebsite\Excel\Excel::CSV);
        }

        if ($format === 'pdf') {
            $bookingLists = BookingList::all();
            $pdf = Pdf::loadView('booking_lists.exports.booking_lists_pdf', compact('bookingLists'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new BookingListsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $bookingLists = BookingList::orderBy('created_at', 'desc')->get();
            return view('booking_lists.exports.booking_lists_print', compact('bookingLists'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
{
    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=booking_lists_sample.csv",
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

        Excel::import($import = new BookingListImport, $request->file('file'));

        if (!empty($import->failures())) {
            return redirect()->back()->with([
                'failures' => $import->failures(),
            ]);
        }

        return redirect()->route('booking_lists.index')->with('success', 'Bookings imported successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
    }
}
}
