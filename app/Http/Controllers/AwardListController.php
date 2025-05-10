<?php

namespace App\Http\Controllers;

use App\Exports\AwardListExport;
use App\Http\Requests\AwardListRequest;
use App\Http\Requests\UpdateAwardListRequest;
use App\Models\AwardList;
use App\Queries\AwardListDataTable;
use App\Repositories\AwardListRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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

        abort(404);
    }
}
