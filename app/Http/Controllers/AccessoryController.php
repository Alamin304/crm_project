<?php

namespace App\Http\Controllers;

use App\Queries\AccessoryDataTable;
use App\Exports\AccessoriesExport;
use App\Http\Requests\AccessoryRequest;
use App\Http\Requests\UpdateAccessoryRequest;
use App\Imports\AccessoryImport;
use App\Models\Accessory;
use App\Repositories\AccessoryRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Throwable;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class AccessoryController extends AppBaseController
{
    private $accessoryRepository;

    public function __construct(AccessoryRepository $accessoryRepo)
    {
        $this->accessoryRepository = $accessoryRepo;
    }
     public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new AccessoryDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('accessories.index');
    }

    public function create()
    {
        $categories = ['Keyboard', 'Mouse', 'Monitor', 'Headset', 'Adapter', 'Cable', 'Dock', 'Charger'];
        $manufacturers = ['Logitech', 'Dell', 'HP', 'Apple', 'Samsung', 'Anker', 'Belkin'];
        $suppliers = ['Tech Supplier Inc', 'Computer World', 'Digital Solutions', 'Global Tech', 'Accessory King'];

        return view('accessories.create', compact('categories', 'manufacturers', 'suppliers'));
    }

    public function store(AccessoryRequest $request)
    {
        $input = $request->all();

        try {
            if ($request->hasFile('image')) {
                $input['image'] = $request->file('image')->store('accessories', 'public');
            }

            $accessory = $this->accessoryRepository->create($input);

            activity()->causedBy(getLoggedInUser())
                ->performedOn($accessory)
                ->useLog('Accessory created.')
                ->log($accessory->accessory_name . ' Accessory Created');

            return $this->sendResponse($accessory, __('messages.accessory.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function show(Accessory $accessory)
    {
        return view('accessories.view', compact('accessory'));
    }

    public function edit(Accessory $accessory)
    {
        $categories = ['Keyboard', 'Mouse', 'Monitor', 'Headset', 'Adapter', 'Cable', 'Dock', 'Charger'];
        $manufacturers = ['Logitech', 'Dell', 'HP', 'Apple', 'Samsung', 'Anker', 'Belkin'];
        $suppliers = ['Tech Supplier Inc', 'Computer World', 'Digital Solutions', 'Global Tech', 'Accessory King'];

        return view('accessories.edit', compact('accessory', 'categories', 'manufacturers', 'suppliers'));
    }

    public function update(Accessory $accessory, UpdateAccessoryRequest $request)
    {
        $input = $request->all();

        try {
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($accessory->image) {
                    Storage::disk('public')->delete($accessory->image);
                }
                $input['image'] = $request->file('image')->store('accessories', 'public');
            }

            $this->accessoryRepository->update($input, $accessory->id);

            activity()->performedOn($accessory)->causedBy(getLoggedInUser())
                ->useLog('Accessory Updated')->log($accessory->accessory_name . ' Accessory updated.');

            return $this->sendSuccess(__('messages.accessory.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function destroy(Accessory $accessory)
    {
        try {
            // Delete image if exists
            if ($accessory->image) {
                Storage::disk('public')->delete($accessory->image);
            }

            $accessory->delete();

            activity()->performedOn($accessory)->causedBy(getLoggedInUser())
                ->useLog('Accessory deleted.')->log($accessory->accessory_name . ' Accessory deleted.');

            return $this->sendSuccess(__('messages.accessory.delete'));
        } catch (Exception $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'accessories_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new AccessoriesExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $accessories = Accessory::all();
            $pdf = Pdf::loadView('accessories.exports.accessories_pdf', compact('accessories'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new AccessoriesExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $accessories = Accessory::orderBy('accessory_name', 'asc')->get();
            return view('accessories.exports.accessories_print', compact('accessories'));
        }

        abort(404);
    }

    public function sampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=accessories_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'accessory_name', 'category_name', 'supplier', 'manufacturer', 'location',
            'model_number', 'order_number', 'purchase_cost', 'purchase_date', 'quantity',
            'min_quantity', 'for_sell', 'selling_price', 'notes'
        ];

        $rows = [
            [
                'Wireless Mouse', 'Mouse', 'Tech Supplier Inc', 'Logitech', 'Warehouse A',
                'M705', 'ORD123', 29.99, '2023-01-15', 10, 2, 1, 39.99, 'Wireless mouse with long battery life'
            ],
            [
                'HDMI Cable', 'Cable', 'Computer World', 'Anker', 'Warehouse B',
                'AK-HDMI-6', 'ORD456', 9.99, '2023-02-20', 50, 10, 0, null, '6ft HDMI cable'
            ]
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
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt,xlsx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');
            $headers = fgetcsv($file);

            $expectedHeaders = [
                'accessory_name', 'category_name', 'supplier', 'manufacturer', 'location',
                'model_number', 'order_number', 'purchase_cost', 'purchase_date', 'quantity',
                'min_quantity', 'for_sell', 'selling_price', 'notes'
            ];

            if (array_map('strtolower', $headers) !== array_map('strtolower', $expectedHeaders)) {
                return redirect()->back()->with('error', 'Invalid file format. Please download the sample file for correct format.');
            }

            fclose($file);

            Excel::import($import = new AccessoryImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('accessories.index')->with('success', 'Accessories imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
