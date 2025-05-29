<?php

namespace App\Http\Controllers;

use App\Exports\BuyRequestExport;
use App\Http\Requests\BuyRequestRequest;
use App\Http\Requests\UpdateBuyRequestRequest;
use App\Models\BuyRequest;
use App\Queries\BuyRequestDataTable;
use App\Repositories\BuyRequestRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

class BuyRequestController extends AppBaseController
{
    private $buyRequestRepository;

    public function __construct(BuyRequestRepository $repo)
    {
        $this->buyRequestRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new BuyRequestDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }

        return view('buy_requests.index');
    }

    public function create()
    {
        return view('buy_requests.create');
    }

    public function store(BuyRequestRequest $request)
    {
        $input = $request->all();
        $input['request_number'] = BuyRequest::generateRequestNumber();

        $buyRequest = $this->buyRequestRepository->create($input);

        return $this->sendResponse($buyRequest, 'Buy Request saved successfully.');
    }

    public function show(BuyRequest $buyRequest)
    {
        return view('buy_requests.view', compact('buyRequest'));
    }

    public function edit(BuyRequest $buyRequest)
    {
        return view('buy_requests.edit', compact('buyRequest'));
    }

    public function update(BuyRequest $buyRequest, UpdateBuyRequestRequest $request)
    {
        $input = $request->all();
        $this->buyRequestRepository->update($input, $buyRequest->id);

        return $this->sendSuccess('Buy Request updated successfully.');
    }

    public function destroy(BuyRequest $buyRequest)
    {
        $buyRequest->delete();
        return $this->sendSuccess('Buy Request deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'buy_requests_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new BuyRequestExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $buyRequests = BuyRequest::with('customer')->get();
            $pdf = Pdf::loadView('buy_requests.exports.buy_requests_pdf', compact('buyRequests'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new BuyRequestExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $buyRequests = BuyRequest::with('customer')->orderBy('created_at', 'desc')->get();
            return view('buy_requests.exports.buy_requests_print', compact('buyRequests'));
        }

        abort(404);
    }

    public function updateStatus(Request $request, BuyRequest $buyRequest)
    {
        $request->validate([
            'status' => 'required|in:submitted,sent,waiting for approval,approved,declined,complete,expired,cancelled'
        ]);

        $buyRequest->status = $request->status;
        $buyRequest->save();

        return $this->sendSuccess('Buy Request status updated successfully.');
    }

    public function updateAddress(Request $request, BuyRequest $buyRequest, $type)
    {
        $validated = $request->validate([
            'street' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'country' => 'required|string'
        ]);

        if ($type === 'bill') {
            $buyRequest->bill_to = $validated;
        } else {
            $buyRequest->ship_to = $validated;
        }

        $buyRequest->save();

        return $this->sendSuccess('Address updated successfully.');
    }
}