<?php

namespace App\Http\Controllers;

use App\Exports\ManufacturingOrderExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\ManufacturingOrderRequest;
use App\Http\Requests\UpdateManufacturingOrderRequest;
use App\Models\ManufacturingOrder;
use App\Queries\ManufacturingOrderDataTable;
use App\Repositories\ManufacturingOrderRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ManufacturingOrderController extends AppBaseController
{
    private $manufacturingOrderRepository;

    public function __construct(ManufacturingOrderRepository $manufacturingOrderRepo)
    {
        $this->manufacturingOrderRepository = $manufacturingOrderRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new ManufacturingOrderDataTable())->get())->make(true);
        }
        return view('manufacturing_orders.index');
    }

    public function create()
    {
        $units = ['Pieces', 'Kilograms', 'Liters', 'Meters', 'Boxes'];
        $responsibles = ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Williams'];
        $boms = \App\Models\BillsOfMaterial::pluck('BOM_code', 'BOM_code')->toArray();
        $routings = \App\Models\Routing::pluck('routing_name', 'routing_name')->toArray();

        return view('manufacturing_orders.create', compact('units', 'responsibles', 'boms', 'routings'));
    }

    public function store(ManufacturingOrderRequest $request)
    {
        $input = $request->all();
        try {
            $manufacturingOrder = $this->manufacturingOrderRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($manufacturingOrder)
                ->useLog('Manufacturing Order created.')
                ->log($manufacturingOrder->product . ' Manufacturing Order Created');
            return $this->sendResponse($manufacturingOrder, __('messages.manufacturing_orders.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function show(ManufacturingOrder $manufacturingOrder)
    {
        return view('manufacturing_orders.view', compact('manufacturingOrder'));
    }

    public function edit(ManufacturingOrder $manufacturingOrder)
    {
        $units = ['Pieces', 'Kilograms', 'Liters', 'Meters', 'Boxes'];
        $responsibles = ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Williams'];
        $boms = \App\Models\BillsOfMaterial::pluck('BOM_code', 'BOM_code')->toArray();
        $routings = \App\Models\Routing::pluck('routing_name', 'routing_name')->toArray();

        return view('manufacturing_orders.edit', compact('manufacturingOrder', 'units', 'responsibles', 'boms', 'routings'));
    }

    public function update(ManufacturingOrder $manufacturingOrder, UpdateManufacturingOrderRequest $request)
    {
        $input = $request->all();
        $manufacturingOrder = $this->manufacturingOrderRepository->update($input, $manufacturingOrder->id);
        activity()->performedOn($manufacturingOrder)->causedBy(getLoggedInUser())
            ->useLog('Manufacturing Order Updated')->log($manufacturingOrder->product . ' Manufacturing Order updated.');
        return $this->sendSuccess(__('messages.manufacturing_orders.saved'));
    }

    public function destroy(ManufacturingOrder $manufacturingOrder)
    {
        try {
            $manufacturingOrder->delete();
            activity()->performedOn($manufacturingOrder)->causedBy(getLoggedInUser())
                ->useLog('Manufacturing Order deleted.')->log($manufacturingOrder->product . ' Manufacturing Order deleted.');
            return $this->sendSuccess(__('messages.manufacturing_orders.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'manufacturing_orders_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new ManufacturingOrderExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $manufacturingOrders = ManufacturingOrder::all();
            $pdf = PDF::loadView('manufacturing_orders.exports.manufacturing_orders_pdf', compact('manufacturingOrders'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new ManufacturingOrderExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $manufacturingOrders = ManufacturingOrder::orderBy('created_at', 'desc')->get();
            return view('manufacturing_orders.exports.manufacturing_orders_print', compact('manufacturingOrders'));
        }

        abort(404);
    }
}
