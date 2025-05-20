<?php

namespace App\Http\Controllers;

use App\Exports\WarrantiesInfoExport;
use App\Exports\WarrantyExport;
use App\Http\Requests\WarrantyRequest;
use App\Http\Requests\UpdateWarrantyRequest;
use App\Imports\WarrantyImport;
use App\Models\Warranty;
use App\Queries\WarrantyDataTable;
use App\Repositories\WarrantyRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class WarrantyController extends AppBaseController
{
    private $warrantyRepository;

    public function __construct(WarrantyRepository $repo)
    {
        $this->warrantyRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new WarrantyDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }

        return view('warranties.index');
    }

    public function create()
    {
        return view('warranties.create');
    }

    public function store(WarrantyRequest $request)
    {
        $input = $request->all();
        $warranty = $this->warrantyRepository->create($input);

        return $this->sendResponse($warranty, 'Warranty saved successfully.');
    }

    public function show(Warranty $warranty)
    {
        return view('warranties.view', compact('warranty'));
    }

    public function edit(Warranty $warranty)
    {
        return view('warranties.edit', compact('warranty'));
    }

    public function update(Warranty $warranty, UpdateWarrantyRequest $request)
    {
        $input = $request->all();
        $this->warrantyRepository->update($input, $warranty->id);

        return $this->sendSuccess('Warranty updated successfully.');
    }

    public function destroy(Warranty $warranty)
    {
        $warranty->delete();
        return $this->sendSuccess('Warranty deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'warranties_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new WarrantyExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $warranties = Warranty::all();
            $pdf = Pdf::loadView('warranties.exports.warranties_pdf', compact('warranties'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new WarrantyExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $warranties = Warranty::orderBy('created_at', 'desc')->get();
            return view('warranties.exports.warranties_print', compact('warranties'));
        }

        abort(404);
    }
    public function updateStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:approved,processing,complete,closed,canceled'
        ]);

        $warranty = Warranty::findOrFail($id);
        $warranty->update(['status' => $request->status]);

        return $this->sendSuccess('Warranty status updated successfully.');
    }

    public function warrantyInformation(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Warranty::select('id', 'date_created', 'customer', 'invoice', 'product_service_name'))
                ->addIndexColumn()
                ->make(true);
        }

        return view('warranties.warranty_info_index');
    }


    public function WarrantiesInfoexport($format)
    {
        $fileName = 'warranty_information_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new WarrantiesInfoExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $warranties = Warranty::orderBy('created_at', 'desc')->get();
            $formatted = $warranties->map(function ($item, $index) {
                return [
                    'id' => $index + 1,
                    'customer' => $item->customer ?? '-',
                    'order_number' => '-',
                    'invoice' => $item->invoice ?? '-',
                    'product_service_name' => $item->product_service_name ?? '-',
                    'rate' => '-',
                    'quantity' => '-',
                    'serial_number' => '-',
                ];
            });
            $pdf = Pdf::loadView('warranties.exports.warranty_info_pdf', ['warranties' => $formatted]);
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new WarrantiesInfoExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $warranties = Warranty::orderBy('created_at', 'desc')->get();
            $formatted = $warranties->map(function ($item, $index) {
                return [
                    'id' => $index + 1,
                    'customer' => $item->customer ?? '-',
                    'order_number' => '-',
                    'invoice' => $item->invoice ?? '-',
                    'product_service_name' => $item->product_service_name ?? '-',
                    'rate' => '-',
                    'quantity' => '-',
                    'serial_number' => '-',
                ];
            });
            return view('warranties.exports.warranty_info_print', ['warranties' => $formatted]);
        }

        abort(404);
    }
    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=warranties_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'claim_code',
            'customer',
            'invoice',
            'product_service_name',
            'warranty_receipt_process',
            'description',
            'client_note',
            'admin_note',
            'status'
        ];

        $rows = [
            [
                'WC-001',
                'John Doe',
                'INV-2023-001',
                'Premium Laptop',
                'Email',
                'Screen replacement under warranty',
                'Need it fixed ASAP',
                'Waiting for parts',
                'processing'
            ],
            [
                'WC-002',
                'Jane Smith',
                'INV-2023-002',
                'Smartphone',
                'In-store',
                'Battery replacement',
                'Phone keeps shutting down',
                'Parts ordered',
                'approved'
            ],
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

            $expectedHeaders = [
                'claim_code',
                'customer',
                'invoice',
                'product_service_name',
                'warranty_receipt_process',
                'description',
                'client_note',
                'admin_note',
                'status'
            ];

            if (array_diff($expectedHeaders, array_map('strtolower', $headers))) {
                fclose($file);
                return redirect()->back()->with('error', 'Invalid file format. Please download the sample CSV for the correct format.');
            }

            fclose($file);

            Excel::import($import = new WarrantyImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('warranties.index')->with('success', 'Warranties imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
