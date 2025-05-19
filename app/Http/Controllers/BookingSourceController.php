<?php

namespace App\Http\Controllers;

use App\Exports\BookingSourceExport;
use App\Http\Requests\BookingSourceRequest;
use App\Http\Requests\UpdateBookingSourceRequest;
use App\Imports\BookingSourceImport;
use App\Models\BookingSource;
use App\Queries\BookingSourceDataTable;
use App\Repositories\BookingSourceRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class BookingSourceController extends AppBaseController
{
    private $bookingSourceRepository;

    public function __construct(BookingSourceRepository $repo)
    {
        $this->bookingSourceRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new BookingSourceDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }

        return view('booking_sources.index');
    }

    public function create()
    {
        return view('booking_sources.create');
    }

    public function store(BookingSourceRequest $request)
    {
        $input = $request->all();
        $bookingSource = $this->bookingSourceRepository->create($input);

        return $this->sendResponse($bookingSource, 'Booking Source saved successfully.');
    }

    public function view(BookingSource $bookingSource)
    {
        return view('booking_sources.view', compact('bookingSource'));
    }

    public function edit(BookingSource $bookingSource)
    {
        return view('booking_sources.edit', compact('bookingSource'));
    }

    public function update(BookingSource $bookingSource, UpdateBookingSourceRequest $request)
    {
        $input = $request->all();
        $this->bookingSourceRepository->update($input, $bookingSource->id);

        return $this->sendSuccess('Booking Source updated successfully.');
    }

    public function destroy(BookingSource $bookingSource)
    {
        $bookingSource->delete();
        return $this->sendSuccess('Booking Source deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'booking_sources_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new BookingSourceExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $bookingSources = BookingSource::all();
            $pdf = Pdf::loadView('booking_sources.exports.booking_sources_pdf', compact('bookingSources'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new BookingSourceExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $bookingSources = BookingSource::orderBy('id')->get();
            return view('booking_sources.exports.booking_sources_print', compact('bookingSources'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=booking_sources_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['booking_type', 'booking_source', 'commission_rate'];
        $rows = [
            ['Online', 'Booking.com', '15.00'],
            ['Corporate', 'Company XYZ', '10.00'],
            ['Travel Agent', 'ABC Travel', '12.50'],
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

        // Prevent duplicate import if groups already exist
        if (\App\Models\BookingSource::exists()) {
            return redirect()->back()->with('error', 'Import failed: booking source already exist in the database.');
        }

        try {
            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');
            $headers = fgetcsv($file);

            $expectedHeaders = ['booking_type', 'booking_source', 'commission_rate'];

            if (array_map('strtolower', $headers) !== array_map('strtolower', $expectedHeaders)) {
                fclose($file);
                return redirect()->back()->with('error', 'Invalid file format. Required headers: booking_type, booking_source, commission_rate.');
            }

            fclose($file);

            Excel::import($import = new BookingSourceImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('booking-sources.index')->with('success', 'Booking sources imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
