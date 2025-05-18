<?php

namespace App\Http\Controllers;

use App\Exports\BookingSourceExport;
use App\Http\Requests\BookingSourceRequest;
use App\Http\Requests\UpdateBookingSourceRequest;
use App\Models\BookingSource;
use App\Queries\BookingSourceDataTable;
use App\Repositories\BookingSourceRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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
}
