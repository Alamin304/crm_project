<?php

namespace App\Http\Controllers;

use App\Exports\BedsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\BedRequest;
use App\Http\Requests\UpdateBedRequest;
use App\Imports\BedImport;
use App\Models\Bed;
use App\Queries\BedDataTable;
use App\Repositories\BedRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class BedController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $bedRepository;

    public function __construct(BedRepository $bedRepo)
    {
        $this->bedRepository = $bedRepo;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new BedDataTable())->get())->make(true);
        }
        return view('beds.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('beds.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BedRequest $request)
    {
        $input = $request->all();
        try {
            $bed = $this->bedRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($bed)
                ->useLog('Bed created.')
                ->log($bed->name . ' Bed Created');
            return $this->sendResponse($bed, __('messages.beds.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bed  $bed
     * @return \Illuminate\Http\Response
     */
    public function view(Bed $bed)
    {
        return view('beds.view', compact('bed'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bed  $bed
     * @return \Illuminate\Http\Response
     */
    public function edit(Bed $bed)
    {
        return view('beds.edit', compact('bed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bed  $bed
     * @return \Illuminate\Http\Response
     */
    public function update(Bed $bed, UpdateBedRequest $updateBedRequest)
    {
        $input = $updateBedRequest->all();
        $bed = $this->bedRepository->update($input, $updateBedRequest->id);
        activity()->performedOn($bed)->causedBy(getLoggedInUser())
            ->useLog('Bed Updated')->log($bed->name . ' Bed updated.');
        return $this->sendSuccess(__('messages.beds.saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bed  $bed
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bed $bed)
    {
        try {
            $bed->delete();
            activity()->performedOn($bed)->causedBy(getLoggedInUser())
                ->useLog('Bed deleted.')->log($bed->name . ' Bed deleted.');
            return $this->sendSuccess(__('messages.beds.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'beds_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new BedsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $beds = Bed::all();
            $pdf = PDF::loadView('beds.exports.beds_pdf', compact('beds'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new BedsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $beds = Bed::orderBy('created_at', 'desc')->get();
            return view('beds.exports.beds_print', compact('beds'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=beds_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['name', 'description'];
        $rows = [
            ['Bed A', 'Near window'],
            ['Bed B', 'Next to entrance'],
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

        if (\App\Models\Bed::exists()) {
            return redirect()->back()->with('error', 'Import failed: Beds already exist in the database.');
        }

        try {
            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');
            $headers = fgetcsv($file);

            $expectedHeaders = ['name', 'description'];

            if (array_map('strtolower', $headers) !== $expectedHeaders) {
                return redirect()->back()->with('error', 'Invalid file format. Required headers: bed_name, description.');
            }

            fclose($file);

            Excel::import($import = new BedImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('beds.index')->with('success', 'Beds imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
