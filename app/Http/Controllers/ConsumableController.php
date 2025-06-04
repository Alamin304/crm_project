<?php

namespace App\Http\Controllers;

use App\Exports\ConsumablesExport;
use App\Http\Requests\ConsumableRequest;
use App\Http\Requests\UpdateConsumableRequest;
use App\Imports\ConsumableImport;
use App\Models\Consumable;
use App\Queries\ConsumableDataTable;
use App\Repositories\ConsumableRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ConsumableController extends AppBaseController
{
    private $consumableRepository;

    public function __construct(ConsumableRepository $consumableRepo)
    {
        $this->consumableRepository = $consumableRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new ConsumableDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('consumables.index');
    }

    public function create()
    {
        $categories = ['Office Supplies', 'Computer Accessories', 'Cleaning Supplies', 'Medical Supplies', 'Electrical'];
        $manufacturers = ['3M', 'HP', 'Dell', 'Brother', 'Canon'];
        $suppliers = ['SupplyCo', 'OfficeWorld', 'TechSupplies', 'Global Suppliers', 'QuickShip'];

        return view('consumables.create', compact('categories', 'manufacturers', 'suppliers'));
    }

    public function store(ConsumableRequest $request)
    {
        $input = $request->all();

        try {
            if ($request->hasFile('image')) {
                $input['image'] = $request->file('image')->store('consumables', 'public');
            }

            $consumable = $this->consumableRepository->create($input);

            activity()->causedBy(getLoggedInUser())
                ->performedOn($consumable)
                ->useLog('Consumable created.')
                ->log($consumable->consumable_name . ' Consumable Created');

            return $this->sendResponse($consumable, __('messages.consumables.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function show(Consumable $consumable)
    {
        return view('consumables.view', compact('consumable'));
    }

    public function edit(Consumable $consumable)
    {
        $categories = ['Office Supplies', 'Computer Accessories', 'Cleaning Supplies', 'Medical Supplies', 'Electrical'];
        $manufacturers = ['3M', 'HP', 'Dell', 'Brother', 'Canon'];
        $suppliers = ['SupplyCo', 'OfficeWorld', 'TechSupplies', 'Global Suppliers', 'QuickShip'];

        return view('consumables.edit', compact('consumable', 'categories', 'manufacturers', 'suppliers'));
    }

    public function update(Consumable $consumable, UpdateConsumableRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($consumable->image) {
                \Storage::disk('public')->delete($consumable->image);
            }
            $input['image'] = $request->file('image')->store('consumables', 'public');
        }

        $this->consumableRepository->update($input, $consumable->id);

        activity()->performedOn($consumable)->causedBy(getLoggedInUser())
            ->useLog('Consumable Updated')->log($consumable->consumable_name . ' Consumable updated.');

        return $this->sendSuccess(__('messages.consumables.saved'));
    }

    public function destroy(Consumable $consumable)
    {
        try {
            // Delete image if exists
            if ($consumable->image) {
                \Storage::disk('public')->delete($consumable->image);
            }

            $consumable->delete();

            activity()->performedOn($consumable)->causedBy(getLoggedInUser())
                ->useLog('Consumable deleted.')->log($consumable->consumable_name . ' Consumable deleted.');

            return $this->sendSuccess(__('messages.consumables.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'consumables_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new ConsumablesExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $consumables = Consumable::all();
            $pdf = Pdf::loadView('consumables.exports.consumables_pdf', compact('consumables'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new ConsumablesExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $consumables = Consumable::orderBy('consumable_name', 'asc')->get();
            return view('consumables.exports.consumables_print', compact('consumables'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=consumables_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'consumable_name',
            'category_name',
            'supplier',
            'manufacturer',
            'location',
            'model_number',
            'order_number',
            'purchase_cost',
            'purchase_date',
            'quantity',
            'min_quantity',
            'for_sell',
            'selling_price',
            'notes'
        ];

        $rows = [
            [
                'Printer Toner',
                'Computer Accessories',
                'TechSupplies',
                'HP',
                'Storage Room',
                'HP-1234',
                'ORD-789',
                89.99,
                '2023-05-15',
                10,
                2,
                0,
                null,
                'For office printers'
            ],
            [
                'Disinfectant Wipes',
                'Cleaning Supplies',
                'SupplyCo',
                '3M',
                'Janitor Closet',
                '3M-456',
                'ORD-101',
                24.99,
                '2023-06-20',
                25,
                5,
                1,
                29.99,
                'Monthly restock'
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
                'consumable_name',
                'category_name',
                'supplier',
                'manufacturer',
                'location',
                'model_number',
                'order_number',
                'purchase_cost',
                'purchase_date',
                'quantity',
                'min_quantity',
                'for_sell',
                'selling_price',
                'notes'
            ];

            if (array_map('strtolower', $headers) !== array_map('strtolower', $expectedHeaders)) {
                return redirect()->back()->with('error', 'Invalid file format. Please download the sample file for correct format.');
            }

            fclose($file);

            Excel::import($import = new ConsumableImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('consumables.index')->with('success', 'Consumables imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }

    public function removeImage(Consumable $consumable)
    {
        try {
            if ($consumable->image) {
                \Storage::disk('public')->delete($consumable->image);
                $consumable->update(['image' => null]);

                return $this->sendSuccess('Image removed successfully.');
            }

            return $this->sendError('No image found to remove.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to remove image: ' . $e->getMessage());
        }
    }
}
