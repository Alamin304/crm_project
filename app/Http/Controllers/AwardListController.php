<?php

namespace App\Http\Controllers;

use App\Exports\AwardListExport;
use App\Http\Requests\AwardListRequest;
use App\Http\Requests\UpdateAwardListRequest;
use App\Imports\AwardListImport;
use App\Models\AwardList;
use App\Queries\AwardListDataTable;
use App\Repositories\AwardListRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class AwardListController extends AppBaseController
{
    private $awardListRepository;

    public function __construct(AwardListRepository $repo)
    {
        $this->awardListRepository = $repo;
    }

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         return DataTables::of((new AwardListDataTable())->get())->make(true);
    //     }

    //     return view('award_lists.index');
    // }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new AwardListDataTable())->get())
                ->editColumn('award_description', function ($row) {
                    return strip_tags($row->award_description);
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('award_lists.index');
    }

    public function create()
    {
        return view('award_lists.create');
    }

    public function store(AwardListRequest $request)
    {
        $input = $request->all();
        $awardList = $this->awardListRepository->create($input);

        return $this->sendResponse($awardList, 'Award saved successfully.');
    }

    public function view(AwardList $awardList)
    {
        return view('award_lists.view', compact('awardList'));
    }

    public function edit(AwardList $awardList)
    {
        return view('award_lists.edit', compact('awardList'));
    }

    public function update(AwardList $awardList, UpdateAwardListRequest $request)
    {
        $input = $request->all();
        $this->awardListRepository->update($input, $awardList->id);

        return $this->sendSuccess('Award updated successfully.');
    }

    public function destroy(AwardList $awardList)
    {
        $awardList->delete();
        return $this->sendSuccess('Award deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'award_lists_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new AwardListExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $awardLists = AwardList::all();
            $pdf = Pdf::loadView('award_lists.exports.award_lists_pdf', compact('awardLists'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new AwardListExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $awardLists = AwardList::orderBy('id')->get();
            return view('award_lists.exports.award_lists_print', compact('awardLists'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=award_lists_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['award_name', 'award_description', 'gift_item', 'date', 'employee_name', 'award_by'];
        $rows = [
            ['Employee of the Month', 'For outstanding performance in Q1', 'Gift Card', '2023-06-15', 'John Doe', 'CEO'],
            ['Best Team Player', 'Excellent collaboration skills', 'Trophy', '2023-06-20', 'Jane Smith', 'HR Manager'],
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

            $expectedHeaders = ['award_name', 'award_description', 'gift_item', 'date', 'employee_name', 'award_by'];

            if (array_map('strtolower', $headers) !== array_map('strtolower', $expectedHeaders)) {
                fclose($file);
                return redirect()->back()->with('error', 'Invalid file format. Required headers: award_name, award_description, gift_item, date, employee_name, award_by.');
            }

            fclose($file);

            Excel::import($import = new AwardListImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('award-lists.index')->with('success', 'Award lists imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
