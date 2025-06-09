<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Http\Controllers\AppBaseController;
use App\Models\Order;
use App\Queries\OrderDataTable;
use App\Repositories\OrderRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends AppBaseController
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new OrderDataTable())->get())->make(true);
        }
        return view('orders.index');
    }

public function destroy($id)
{
    try {
        $order = $this->orderRepository->find($id);
        $order->delete();

        return $this->sendSuccess(__('messages.order.deleted_successfully'));
    } catch (\Exception $e) {
        return $this->sendError(__('messages.order.not_found'), 404);
    }
}

    public function export($format)
    {
        $fileName = 'orders_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new OrdersExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $orders = Order::all();
            $pdf = Pdf::loadView('orders.exports.orders_pdf', compact('orders'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new OrdersExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $orders = Order::orderBy('created_at', 'desc')->get();
            return view('orders.exports.orders_print', compact('orders'));
        }

        abort(404);
    }
}
