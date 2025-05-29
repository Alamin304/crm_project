<?php

namespace App\Http\Controllers;

use App\Exports\RentalRequestExport;
use App\Http\Requests\RentalRequestRequest;
use App\Http\Requests\UpdateRentalRequestRequest;
use App\Models\RentalRequest;
use App\Queries\RentalRequestDataTable;
use App\Repositories\RentalRequestRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

class RentalRequestController extends AppBaseController
{
    private $rentalRequestRepository;

    public function __construct(RentalRequestRepository $repo)
    {
        $this->rentalRequestRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new RentalRequestDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }

        return view('rental_requests.index');
    }

    public function create()
    {
        return view('rental_requests.create');
    }

    public function store(RentalRequestRequest $request)
    {
        $input = $request->all();
        $input['request_number'] = RentalRequest::generateRequestNumber();

        $rentalRequest = $this->rentalRequestRepository->create($input);

        return $this->sendResponse($rentalRequest, 'Rental Request saved successfully.');
    }

    public function show(RentalRequest $rentalRequest)
    {
        return view('rental_requests.view', compact('rentalRequest'));
    }

    public function edit(RentalRequest $rentalRequest)
    {
        return view('rental_requests.edit', compact('rentalRequest'));
    }

    public function update(RentalRequest $rentalRequest, UpdateRentalRequestRequest $request)
    {
        $input = $request->all();
        $this->rentalRequestRepository->update($input, $rentalRequest->id);

        return $this->sendSuccess('Rental Request updated successfully.');
    }

    public function destroy(RentalRequest $rentalRequest)
    {
        $rentalRequest->delete();
        return $this->sendSuccess('Rental Request deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'rental_requests_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new RentalRequestExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $rentalRequests = RentalRequest::with('customer')->get();
            $pdf = Pdf::loadView('rental_requests.exports.rental_requests_pdf', compact('rentalRequests'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new RentalRequestExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $rentalRequests = RentalRequest::with('customer')->orderBy('created_at', 'desc')->get();
            return view('rental_requests.exports.rental_requests_print', compact('rentalRequests'));
        }

        abort(404);
    }

    public function updateStatus(Request $request, RentalRequest $rentalRequest)
    {
        $rentalRequest->status = $request->status;
        $rentalRequest->save();

        return $this->sendSuccess('Rental Request status updated successfully.');
    }

    public function updateAddress(Request $request, RentalRequest $rentalRequest, $type)
    {
        $validated = $request->validate([
            'street' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'country' => 'required|string'
        ]);

        if ($type === 'bill') {
            $rentalRequest->bill_to = $validated;
        } else {
            $rentalRequest->ship_to = $validated;
        }

        $rentalRequest->save();

        return $this->sendSuccess('Address updated successfully.');
    }
}
