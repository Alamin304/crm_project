<?php

namespace App\Http\Controllers;

use App\Exports\WorkOrdersExport;
use App\Models\WorkOrder;
use App\Queries\WorkOrderDataTable;
use App\Repositories\WorkOrderRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class WorkOrderController extends AppBaseController
{
    private $workOrderRepository;

    public function __construct(WorkOrderRepository $workOrderRepo)
    {
        $this->workOrderRepository = $workOrderRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new WorkOrderDataTable())->get())->make(true);
        }
        return view('work_orders.index');
    }

    public function export($format)
    {
        $fileName = 'work_orders_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new WorkOrdersExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $workOrders = WorkOrder::all();
            $pdf = Pdf::loadView('work_orders.exports.work_orders_pdf', compact('workOrders'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new WorkOrdersExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $workOrders = WorkOrder::orderBy('created_at', 'desc')->get();
            return view('work_orders.exports.work_orders_print', compact('workOrders'));
        }

        abort(404);
    }
}
