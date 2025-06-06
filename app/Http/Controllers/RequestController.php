<?php

namespace App\Http\Controllers;

use App\Exports\RequestsExport;
use App\Http\Controllers\AppBaseController;
use App\Models\Request;
use App\Queries\RequestDataTable;
use App\Repositories\RequestRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request as HttpRequest;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;


class RequestController extends AppBaseController
{
    private $requestRepository;

    public function __construct(RequestRepository $requestRepo)
    {
        $this->requestRepository = $requestRepo;
    }

    public function index(HttpRequest $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new RequestDataTable())->get())
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return ucfirst($row->status);
                })
                ->make(true);
        }
        return view('requests.index');
    }

    public function create()
    {
        $assets = [
            'Laptop' => 'Laptop',
            'Monitor' => 'Monitor',
            'Keyboard' => 'Keyboard',
            'Mouse' => 'Mouse',
            'Headphones' => 'Headphones',
            'Docking Station' => 'Docking Station'
        ];

        return view('requests.create', compact('assets'));
    }

    public function store(HttpRequest $request)
    {
        $input = $request->all();
        try {
            $requestModel = $this->requestRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($requestModel)
                ->useLog('Request created.')
                ->log($requestModel->title . ' Request Created');
            return $this->sendResponse($requestModel, __('messages.requests.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function show(Request $requestModel)
    {
        return view('requests.view', compact('requestModel'));
    }

    public function edit(HttpRequest $requestModel)
    {
        $assets = [
            'Laptop' => 'Laptop',
            'Monitor' => 'Monitor',
            'Keyboard' => 'Keyboard',
            'Mouse' => 'Mouse',
            'Headphones' => 'Headphones',
            'Docking Station' => 'Docking Station'
        ];

        $statuses = [
            'new' => 'New',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ];

        return view('requests.edit', compact('requestModel', 'assets', 'statuses'));
    }

    public function update(Request $requestModel, HttpRequest $request)
    {
        $input = $request->all();
        $this->requestRepository->update($input, $requestModel->id);
        activity()->performedOn($requestModel)->causedBy(getLoggedInUser())
            ->useLog('Request Updated')->log($requestModel->title . ' Request updated.');
        return $this->sendSuccess(__('messages.requests.saved'));
    }

    public function destroy(Request $requestModel)
    {
        try {
            $requestModel->delete();
            activity()->performedOn($requestModel)->causedBy(getLoggedInUser())
                ->useLog('Request deleted.')->log($requestModel->title . ' Request deleted.');
            return $this->sendSuccess(__('messages.requests.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'requests_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new RequestsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $requests = Request::all();
            $pdf = Pdf::loadView('requests.exports.requests_pdf', compact('requests'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new RequestsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $requests = Request::orderBy('title', 'asc')->get();
            return view('requests.exports.requests_print', compact('requests'));
        }

        abort(404);
    }
}
