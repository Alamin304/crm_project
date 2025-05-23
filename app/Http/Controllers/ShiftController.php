<?php

namespace App\Http\Controllers;

use App\Exports\ShiftsExport;
use App\Queries\ShiftDataTable;
use Illuminate\Http\Request;
use App\Repositories\ShiftRepository;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Exception;
use App\Http\Requests\ShiftRequest;
use App\Models\Shift;
use App\Http\Requests\UpdateShiftRequest;
use App\Imports\ShiftImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ShiftController extends AppBaseController
{
    /**
     * @var ShiftRepository
     */
    private $shiftRepository;
    public function __construct(ShiftRepository $shiftRepo)
    {
        $this->shiftRepository = $shiftRepo;
    }
    /**
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new ShiftDataTable())->get($request->only(['group'])))
                ->addIndexColumn()
                ->make(true);
        }
        return view('shifts.index');
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function store(ShiftRequest $request)
    {
        $input = $request->all();

        try {
            $shift = $this->shiftRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($shift)
                ->useLog('Shift created.')
                ->log($shift->name);
            Flash::success(__('messages.shifts.saved'));

            return $this->sendResponse($shift->name, __('messages.shifts.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }
    public function destroy(Shift $shift)
    {
        try {
            $shift->delete();
            activity()->performedOn($shift)->causedBy(getLoggedInUser())
                ->useLog('Shift deleted.')->log($shift->name . ' deleted.');
            return $this->sendSuccess(__('messages.shifts.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function view(Shift $shift)
    {
        return view('shifts.view', compact(['shift']));
    }
    public function edit(Shift $shift)
    {
        return view('shifts.edit', compact(['shift']));
    }
    public function update(Shift $shift, UpdateShiftRequest $updateShiftRequest)
    {
        $input = $updateShiftRequest->all();
        $updateShift = $this->shiftRepository->update($input, $updateShiftRequest->id);
        activity()->performedOn($updateShift)->causedBy(getLoggedInUser())
            ->useLog('Shift Updated')->log($updateShift->name . ' Shift updated.');
        Flash::success(__('messages.shifts.saved'));
        return $this->sendSuccess(__('messages.shifts.saved'));
    }

    public function export($format)
    {
        $fileName = 'shifts_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new ShiftsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $shifts = Shift::all();
            $pdf = Pdf::loadView('shifts.exports.shifts_pdf', compact('shifts'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new ShiftsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $shifts = Shift::orderBy('id')->get();
            return view('shifts.exports.shifts_print', compact('shifts'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=shifts_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['name', 'shift_start_time', 'shift_end_time', 'description'];
        $rows = [
            ['Morning Shift', '08:00', '16:00', 'Standard morning working hours'],
            ['Evening Shift', '16:00', '00:00', 'Evening shift with night differential'],
            ['Night Shift', '00:00', '08:00', 'Overnight shift'],
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

            $expectedHeaders = ['name', 'shift_start_time', 'shift_end_time', 'description'];

            if (array_map('strtolower', $headers) !== array_map('strtolower', $expectedHeaders)) {
                fclose($file);
                return redirect()->back()->with('error', 'Invalid file format. Required headers: name, shift_start_time, shift_end_time, description.');
            }

            fclose($file);

            Excel::import($import = new ShiftImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('shifts.index')->with('success', 'Shifts imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
