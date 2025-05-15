<?php

namespace App\Http\Controllers;

use App\Exports\ComplementariesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\ComplementaryRequest;
use App\Http\Requests\UpdateComplementaryRequest;
use App\Models\Complementary;
use App\Queries\ComplementaryDataTable;
use App\Repositories\ComplementaryRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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

        abort(404);
    }
}
