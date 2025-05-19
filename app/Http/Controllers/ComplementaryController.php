<?php

namespace App\Http\Controllers;

use App\Exports\ComplementariesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\ComplementaryRequest;
use App\Http\Requests\UpdateComplementaryRequest;
use App\Imports\ComplementaryImport;
use App\Models\Complementary;
use App\Queries\ComplementaryDataTable;
use App\Repositories\ComplementaryRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class ComplementaryController extends AppBaseController
{
    private $complementaryRepository;

    public function __construct(ComplementaryRepository $repo)
    {
        $this->complementaryRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new ComplementaryDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }

        return view('complementaries.index');
    }

    public function create()
    {
        return view('complementaries.create');
    }

    public function store(ComplementaryRequest $request)
    {
        $input = $request->all();
        $complementary = $this->complementaryRepository->create($input);

        return $this->sendResponse($complementary, 'Complementary saved successfully.');
    }

    public function view(Complementary $complementary)
    {
        return view('complementaries.view', compact('complementary'));
    }

    public function edit(Complementary $complementary)
    {
        return view('complementaries.edit', compact('complementary'));
    }

    public function update(Complementary $complementary, UpdateComplementaryRequest $request)
    {
        $input = $request->all();
        $this->complementaryRepository->update($input, $complementary->id);

        return $this->sendSuccess('Complementary updated successfully.');
    }

    public function destroy(Complementary $complementary)
    {
        $complementary->delete();
        return $this->sendSuccess('Complementary deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'complementaries_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new ComplementariesExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $complementaries = Complementary::all();
            $pdf = PDF::loadView('complementaries.exports.complementaries_pdf', compact('complementaries'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new ComplementariesExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $complementaries = Complementary::orderBy('id')->get();
            return view('complementaries.exports.complementaries_print', compact('complementaries'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=complementaries_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['room_type', 'complementary', 'rate'];
        $rows = [
            ['Deluxe', 'Breakfast', '15.00'],
            ['Standard', 'WiFi', '5.00'],
            ['Suite', 'Airport Transfer', '25.00'],
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

        // Prevent duplicate import if groups already exist
        if (\App\Models\Complementary::exists()) {
            return redirect()->back()->with('error', 'Import failed: Complementary already exist in the database.');
        }

        try {
            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');
            $headers = fgetcsv($file);

            $expectedHeaders = ['room_type', 'complementary', 'rate'];

            if (array_map('strtolower', $headers) !== array_map('strtolower', $expectedHeaders)) {
                fclose($file);
                return redirect()->back()->with('error', 'Invalid file format. Required headers: room_type, complementary, rate.');
            }

            fclose($file);

            Excel::import($import = new ComplementaryImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('complementaries.index')->with('success', 'Complementaries imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
