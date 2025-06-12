<?php

namespace App\Http\Controllers;

use App\Exports\RoutingsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\RoutingRequest;
use App\Http\Requests\UpdateRoutingRequest;
use App\Models\Routing;
use App\Queries\RoutingDataTable;
use App\Repositories\RoutingRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class RoutingController extends AppBaseController
{
    private $routingRepository;

    public function __construct(RoutingRepository $routingRepo)
    {
        $this->routingRepository = $routingRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new RoutingDataTable())->get())->make(true);
        }
        return view('routings.index');
    }

    public function create()
    {
        return view('routings.create');
    }

    public function store(RoutingRequest $request)
    {
        $input = $request->all();
        try {
            $routing = $this->routingRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($routing)
                ->useLog('Routing created.')
                ->log($routing->routing_name . ' Routing Created');
            return $this->sendResponse($routing, __('messages.routings.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(Routing $routing)
    {
        return view('routings.view', compact('routing'));
    }

    public function edit(Routing $routing)
    {
        return view('routings.edit', compact('routing'));
    }

    public function update(Routing $routing, UpdateRoutingRequest $updateRoutingRequest)
    {
        $input = $updateRoutingRequest->all();
        $routing = $this->routingRepository->update($input, $updateRoutingRequest->id);
        activity()->performedOn($routing)->causedBy(getLoggedInUser())
            ->useLog('Routing Updated')->log($routing->routing_name . ' Routing updated.');
        return $this->sendSuccess(__('messages.routings.saved'));
    }

    public function destroy(Routing $routing)
    {
        try {
            $routing->delete();
            activity()->performedOn($routing)->causedBy(getLoggedInUser())
                ->useLog('Routing deleted.')->log($routing->routing_name . ' Routing deleted.');
            return $this->sendSuccess(__('messages.routings.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'routings_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new RoutingsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $routings = Routing::all();
            $pdf = PDF::loadView('routings.exports.routings_pdf', compact('routings'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new RoutingsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $routings = Routing::orderBy('created_at', 'desc')->get();
            return view('routings.exports.routings_print', compact('routings'));
        }

        abort(404);
    }
}
