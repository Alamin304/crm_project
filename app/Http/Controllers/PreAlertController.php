<?php

namespace App\Http\Controllers;

use App\Exports\PreAlertsExport;
use App\Models\PreAlert;
use App\Queries\PreAlertDataTable;
use App\Repositories\PreAlertRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PreAlertController extends AppBaseController
{
    private $preAlertRepository;

    public function __construct(PreAlertRepository $preAlertRepo)
    {
        $this->preAlertRepository = $preAlertRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new PreAlertDataTable())->get())->make(true);
        }
        return view('pre_alerts.index');
    }

    public function export($format)
    {
        $fileName = 'pre_alerts_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new PreAlertsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $preAlerts = PreAlert::all();
            $pdf = Pdf::loadView('pre_alerts.exports.pre_alerts_pdf', compact('preAlerts'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new PreAlertsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $preAlerts = PreAlert::orderBy('created_at', 'desc')->get();
            return view('pre_alerts.exports.pre_alerts_print', compact('preAlerts'));
        }

        abort(404);
    }
}
