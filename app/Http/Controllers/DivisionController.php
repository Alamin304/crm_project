<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Http\Requests\DivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DivisionsExport;
use App\Imports\DivisionImport;
use App\Queries\DivisionDataTable;
use App\Repositories\DivisionRepository;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DivisionController extends AppBaseController
{
    private $divisionRepository;

    public function __construct(DivisionRepository $divisionRepo)
    {
        $this->divisionRepository = $divisionRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new DivisionDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('divisions.index');
    }

    public function create()
    {
        return view('divisions.create');
    }

    public function store(DivisionRequest $request)
    {
        $input = $request->all();
        try {
            $division = $this->divisionRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($division)
                ->useLog('Division created.')
                ->log($division->name . ' Division Created');
            return $this->sendResponse($division, __('messages.divisions.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(Division $division)
    {
        return view('divisions.view', compact('division'));
    }

    public function edit(Division $division)
    {
        return view('divisions.edit', compact('division'));
    }

    public function update(Division $division, UpdateDivisionRequest $request)
    {
        $input = $request->all();
        $this->divisionRepository->update($input, $division->id);
        activity()->performedOn($division)->causedBy(getLoggedInUser())
            ->useLog('Division Updated')->log($division->name . ' Division updated.');
        return $this->sendSuccess(__('messages.divisions.saved'));
    }

    public function destroy(Division $division)
    {
        try {
            $division->delete();
            activity()->performedOn($division)->causedBy(getLoggedInUser())
                ->useLog('Division deleted.')->log($division->name . ' Division deleted.');
            return $this->sendSuccess(__('messages.divisions.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'divisions_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new DivisionsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $divisions = Division::all();
            $pdf = PDF::loadView('divisions.exports.divisions_pdf', compact('divisions'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new DivisionsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $divisions = Division::orderBy('name', 'asc')->get();
            return view('divisions.exports.divisions_print', compact('divisions'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=divisions_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['name', 'description'];
        $rows = [
            ['Division A', 'This is a sample division.'],
            ['Division B', 'This is another sample.'],
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

    public function importCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt,xlsx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
 // Prevent duplicate import if groups already exist
        if (\App\Models\Division::exists()) {
            return redirect()->back()->with('error', 'Import failed: Groups already exist in the database.');
        }
        try {
            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');
            $headers = fgetcsv($file);

            $expectedHeaders = ['name', 'description'];

            if (array_map('strtolower', $headers) !== $expectedHeaders) {
                return redirect()->back()->with('error', 'Invalid file format. Required headers: name, description.');
            }

            fclose($file);

            Excel::import($import = new DivisionImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('divisions.index')->with('success', 'Divisions imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
