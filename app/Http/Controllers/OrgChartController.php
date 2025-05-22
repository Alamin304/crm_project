<?php

namespace App\Http\Controllers;

use App\Exports\OrgChartExport;
use App\Http\Requests\OrgChartRequest;
use App\Http\Requests\UpdateOrgChartRequest;
use App\Models\OrgChart;
use App\Queries\OrgChartDataTable;
use App\Repositories\OrgChartRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OrgChartController extends AppBaseController
{
    private $orgChartRepository;

    public function __construct(OrgChartRepository $repo)
    {
        $this->orgChartRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new OrgChartDataTable())->get())
                ->editColumn('encryption', fn($row) => ucfirst($row->encryption))
                ->addIndexColumn()
                ->make(true);
        }

        return view('org_charts.index');
    }

    // public function create()
    // {
    //     $orgUnits = OrgChart::pluck('name', 'id')->toArray();
    //     return view('org_charts.create', compact('orgUnits'));
    // }

    public function create()
    {
        // Dummy values for dropdowns
        $dummyManagers = [
            'Manager A',
            'Manager B',
            'Manager C',
        ];

        $dummyUnits = [
            'Unit 1',
            'Unit 2',
            'Unit 3',
        ];

        return view('org_charts.create', compact('dummyManagers', 'dummyUnits'));
    }

    public function store(OrgChartRequest $request)
    {
        $input = $request->all();
        $orgChart = $this->orgChartRepository->create($input);
        return $this->sendResponse($orgChart, 'Org Chart saved successfully.');
    }

    public function view(OrgChart $orgChart)
    {
        if ($orgChart->parent_unit) {
            $orgChart->parent_name = OrgChart::find($orgChart->parent_unit)?->name;
        }

        return view('org_charts.view', compact('orgChart'));
    }

    // public function edit(OrgChart $orgChart)
    // {
    //     $orgUnits = OrgChart::where('id', '!=', $orgChart->id)->pluck('name', 'id')->toArray();
    //     return view('org_charts.edit', compact('orgChart', 'orgUnits'));
    // }
    public function edit(OrgChart $orgChart)
    {
        $dummyManagers = [
            'Manager A',
            'Manager B',
            'Manager C',
        ];

        $dummyUnits = [
            'Unit 1',
            'Unit 2',
            'Unit 3',
        ];

        return view('org_charts.edit', compact('orgChart', 'dummyManagers', 'dummyUnits'));
    }

    public function update(UpdateOrgChartRequest $request, OrgChart $orgChart)
    {
        $input = $request->all();
        $this->orgChartRepository->update($input, $orgChart->id);
        return $this->sendSuccess('Org Chart updated successfully.');
    }

    public function destroy(OrgChart $orgChart)
    {
        $orgChart->delete();
        return $this->sendSuccess('Org Chart deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'org_chart_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new OrgChartExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $orgCharts = OrgChart::all();
            $pdf = Pdf::loadView('org_charts.exports.org_charts_pdf', compact('orgCharts'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new OrgChartExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $orgCharts = OrgChart::orderBy('id')->get();
            return view('org_charts.exports.org_charts_print', compact('orgCharts'));
        }

        abort(404);
    }
}
