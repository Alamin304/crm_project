<?php

namespace App\Http\Controllers;

use App\Exports\BusinessBrokerExport;
use App\Http\Requests\BusinessBrokerRequest;
use App\Http\Requests\UpdateBusinessBrokerRequest;
use App\Models\BusinessBroker;
use App\Queries\BusinessBrokerDataTable;
use App\Repositories\BusinessBrokerRepository;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessBrokerController extends AppBaseController
{
    private $businessBrokerRepository;

    public function __construct(BusinessBrokerRepository $repo)
    {
        $this->businessBrokerRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new BusinessBrokerDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }

        return view('business_brokers.index');
    }

    public function create()
    {
        return view('business_brokers.create');
    }

    public function store(BusinessBrokerRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('profile_image')) {
            $input['profile_image'] = $request->file('profile_image')->store('business_brokers', 'public');
        }

        if ($request->hasFile('attachment')) {
            $input['attachment'] = $request->file('attachment')->store('business_brokers/attachments', 'public');
        }

        $broker = $this->businessBrokerRepository->create($input);

        return $this->sendResponse($broker, 'Business Broker saved successfully.');
    }

    public function show(BusinessBroker $businessBroker)
    {
        return view('business_brokers.view', compact('businessBroker'));
    }

    public function edit(BusinessBroker $businessBroker)
    {
        return view('business_brokers.edit', compact('businessBroker'));
    }

    public function update(BusinessBroker $businessBroker, UpdateBusinessBrokerRequest $request)
    {
        $input = $request->all();

        $this->businessBrokerRepository->update($input, $businessBroker->id);

        return $this->sendSuccess('Business Broker updated successfully.');
    }

    public function destroy(BusinessBroker $businessBroker)
    {
        if ($businessBroker->profile_image) {
            Storage::disk('public')->delete($businessBroker->profile_image);
        }

        if ($businessBroker->attachment) {
            Storage::disk('public')->delete($businessBroker->attachment);
        }

        $businessBroker->delete();
        return $this->sendSuccess('Business Broker deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'business_brokers_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new BusinessBrokerExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $brokers = BusinessBroker::all();
            $pdf = Pdf::loadView('business_brokers.exports.brokers_pdf', compact('brokers'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new BusinessBrokerExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $brokers = BusinessBroker::orderBy('created_at', 'desc')->get();
            return view('business_brokers.exports.brokers_print', compact('brokers'));
        }

        abort(404);
    }

    // public function updateStatus(Request $request, BusinessBroker $businessBroker)
    // {
    //     $businessBroker->update(['is_active' => $request->status]);
    //     return $this->sendSuccess('Status updated successfully.');
    // }

    public function updateStatus(Request $request, BusinessBroker $businessBroker)
    {
        $businessBroker->is_active = $request->is_active;
        $businessBroker->save();

        return response()->json([
            'success' => true,
            'message' => 'Business Broker status updated successfully.',
        ]);
    }
    

    public function downloadAttachment(BusinessBroker $businessBroker)
    {
        $filePath = $businessBroker->attachment;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        return Storage::disk('public')->download($filePath);
    }
}
