<?php

namespace App\Http\Controllers;

use App\Exports\WakeUpCallsExport;
use App\Models\WakeUpCall;
use Illuminate\Http\Request;
use App\Queries\WakeUpCallDataTable;
use App\Repositories\WakeUpCallRepository;
use App\Http\Requests\WakeUpCallRequest;
use App\Http\Requests\UpdateWakeUpCallRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class WakeUpCallController extends AppBaseController
{
    private $wakeUpCallRepository;

    public function __construct(WakeUpCallRepository $wakeUpCallRepository)
    {
        $this->wakeUpCallRepository = $wakeUpCallRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new WakeUpCallDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('wake_up_calls.index');
    }

    public function create()
    {
        return view('wake_up_calls.create');
    }

    public function store(WakeUpCallRequest $request)
    {
        $input = $request->all();

        try {
            $call = $this->wakeUpCallRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($call)
                ->useLog('Wake Up Call created.')
                ->log($call->customer_name . ' Wake Up Call Created');
            return $this->sendResponse($call, __('messages.wake_up_calls.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(WakeUpCall $wakeUpCall)
    {
        return view('wake_up_calls.view', compact('wakeUpCall'));
    }

    public function edit(WakeUpCall $wakeUpCall)
    {
        return view('wake_up_calls.edit', compact('wakeUpCall'));
    }

    public function update(WakeUpCall $wakeUpCall, UpdateWakeUpCallRequest $request)
    {
        $input = $request->all();
        $call = $this->wakeUpCallRepository->update($input, $wakeUpCall->id);

        activity()->performedOn($call)->causedBy(getLoggedInUser())
            ->useLog('Wake Up Call Updated')->log($call->customer_name . ' Wake Up Call updated.');

        return $this->sendSuccess(__('messages.wake_up_calls.saved'));
    }

    public function destroy(WakeUpCall $wakeUpCall)
    {
        try {
            $wakeUpCall->delete();
            activity()->performedOn($wakeUpCall)->causedBy(getLoggedInUser())
                ->useLog('Wake Up Call deleted.')->log($wakeUpCall->customer_name . ' Wake Up Call deleted.');
            return $this->sendSuccess(__('messages.wake_up_calls.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'wake_up_calls_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new WakeUpCallsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $wakeUpCalls = WakeUpCall::all();
            $pdf = Pdf::loadView('wake_up_calls.exports.wake_up_calls_pdf', compact('wakeUpCalls'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new WakeUpCallsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $wakeUpCalls = WakeUpCall::orderBy('created_at', 'desc')->get();
            return view('wake_up_calls.exports.wake_up_calls_print', compact('wakeUpCalls'));
        }

        abort(404);
    }
}
