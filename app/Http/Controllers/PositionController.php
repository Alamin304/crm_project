<?php

namespace App\Http\Controllers;

use App\Exports\PositionsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\PositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Imports\PositionImport;
use App\Models\Position;
use App\Queries\PositionDataTable;
use App\Repositories\PositionRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends AppBaseController
{
    private $positionRepository;

    public function __construct(PositionRepository $positionRepo)
    {
        $this->positionRepository = $positionRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new PositionDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }

        return view('positions.index');
    }

    public function create()
    {
        return view('positions.create');
    }

    public function store(PositionRequest $request)
    {
        $input = $request->all();
        try {
            $position = $this->positionRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($position)
                ->useLog('Position created.')
                ->log($position->name . ' Position Created');
            return $this->sendResponse($position, __('messages.positions.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(Position $position)
    {
        return view('positions.view', compact('position'));
    }

    public function edit(Position $position)
    {
        return view('positions.edit', compact('position'));
    }

    public function update(Position $position, UpdatePositionRequest $request)
    {
        $input = $request->all();
        $position = $this->positionRepository->update($input, $request->id);
        activity()->performedOn($position)->causedBy(getLoggedInUser())
            ->useLog('Position Updated')->log($position->name . ' Position updated.');
        return $this->sendSuccess(__('messages.positions.saved'));
    }

    public function destroy(Position $position)
    {
        try {
            $position->delete();
            activity()->performedOn($position)->causedBy(getLoggedInUser())
                ->useLog('Position deleted.')->log($position->name . ' Position deleted.');
            return $this->sendSuccess(__('messages.positions.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed to delete! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'positions_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new PositionsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $positions = Position::all();
            $pdf = Pdf::loadView('positions.exports.positions_pdf', compact('positions'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new PositionsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $positions = Position::orderBy('id')->get();
            return view('positions.exports.positions_print', compact('positions'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=positions_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['name', 'details', 'status'];
        $rows = [
            ['Manager', 'Department head position', '1'],
            ['Supervisor', 'Team leader position', '1'],
            ['Assistant', 'Support staff position', '0'],
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

            $expectedHeaders = ['name', 'details', 'status'];

            if (array_map('strtolower', $headers) !== array_map('strtolower', $expectedHeaders)) {
                fclose($file);
                return redirect()->back()->with('error', 'Invalid file format. Required headers: name, details, status.');
            }

            fclose($file);

            Excel::import($import = new PositionImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('positions.index')->with('success', 'Positions imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
