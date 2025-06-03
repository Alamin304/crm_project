<?php

namespace App\Http\Controllers;

use App\Exports\SecondAssetsExport;
use App\Http\Requests\CreateSecondAssetRequest;
use App\Http\Requests\UpdateSecondAssetRequest;
use App\Imports\SecondAssetImport;
use App\Models\SecondAsset;
use App\Queries\SecondAssetDataTable;
use App\Repositories\SecondAssetRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SecondAssetController extends AppBaseController
{
    private $secondAssetRepository;

    public function __construct(SecondAssetRepository $secondAssetRepo)
    {
        $this->secondAssetRepository = $secondAssetRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new SecondAssetDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('second_assets.index');
    }

    public function create()
    {
        $models = ['Model A', 'Model B', 'Model C', 'Model D'];
        $suppliers = ['Supplier X', 'Supplier Y', 'Supplier Z'];
        $locations = ['Location 1', 'Location 2', 'Location 3'];
        $units = ['Day', 'Week', 'Month', 'Year'];

        return view('second_assets.create', compact('models', 'suppliers', 'locations', 'units'));
    }

    public function store(CreateSecondAssetRequest $request)
    {
        $input = $request->all();

        try {
            $secondAsset = $this->secondAssetRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($secondAsset)
                ->useLog('Second Asset created.')
                ->log($secondAsset->asset_name . ' Second Asset Created');
            return $this->sendResponse($secondAsset, __('messages.second_assets.saved'));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function show(SecondAsset $secondAsset)
    {
        return view('second_assets.view', compact('secondAsset'));
    }

    public function edit(SecondAsset $secondAsset)
    {
        $models = ['Model A', 'Model B', 'Model C', 'Model D'];
        $suppliers = ['Supplier X', 'Supplier Y', 'Supplier Z'];
        $locations = ['Location 1', 'Location 2', 'Location 3'];
        $units = ['Day', 'Week', 'Month', 'Year'];

        return view('second_assets.edit', compact('secondAsset', 'models', 'suppliers', 'locations', 'units'));
    }

    public function update(SecondAsset $secondAsset, UpdateSecondAssetRequest $request)
    {
        $input = $request->all();
        $this->secondAssetRepository->update($input, $secondAsset->id);
        activity()->performedOn($secondAsset)->causedBy(getLoggedInUser())
            ->useLog('Second Asset Updated')->log($secondAsset->asset_name . ' Second Asset updated.');
        return $this->sendSuccess(__('messages.second_assets.updated'));
    }

    public function destroy(SecondAsset $secondAsset)
    {
        try {
            $secondAsset->delete();
            activity()->performedOn($secondAsset)->causedBy(getLoggedInUser())
                ->useLog('Second Asset deleted.')->log($secondAsset->asset_name . ' Second Asset deleted.');
            return $this->sendSuccess(__('messages.second_assets.deleted'));
        } catch (Exception $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'second_assets_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new SecondAssetsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $secondAssets = SecondAsset::all();
            $pdf = Pdf::loadView('second_assets.exports.second_assets_pdf', compact('secondAssets'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new SecondAssetsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $secondAssets = SecondAsset::orderBy('asset_name', 'asc')->get();
            return view('second_assets.exports.second_assets_print', compact('secondAssets'));
        }

        abort(404);
    }

    public function downloadSampleCsv()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=second_assets_sample.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['asset_name', 'serial_number', 'model', 'status', 'location', 'supplier', 'purchase_date', 'purchase_cost', 'order_number', 'warranty', 'requestable', 'for_sell', 'selling_price', 'for_rent', 'rental_price', 'minimum_renting_price', 'unit', 'description'];
        $rows = [
            ['Asset A', 'SN12345', 'Model X', 'ready', 'Warehouse 1', 'Supplier A', '2024-01-01', 1000, 'ORD001', 12, 1, 0, '', 0, '', '', '', 'Sample description'],
            ['Asset B', 'SN54321', 'Model Y', 'operational', 'Warehouse 2', 'Supplier B', '2024-02-01', 1500, 'ORD002', 24, 0, 1, 500, 0, '', '', '', 'Another sample'],
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

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx|max:2048',
        ]);

        if (\App\Models\SecondAsset::exists()) {
            return redirect()->back()->with('error', 'Import failed: Second assets already exist in the database.');
        }

        try {
            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');
            $headers = fgetcsv($file);

            $expectedHeaders = ['asset_name', 'serial_number', 'model', 'status', 'location', 'supplier', 'purchase_date', 'purchase_cost', 'order_number', 'warranty', 'requestable', 'for_sell', 'selling_price', 'for_rent', 'rental_price', 'minimum_renting_price', 'unit', 'description'];

            if (array_map('strtolower', $headers) !== $expectedHeaders) {
                return redirect()->back()->with('error', 'Invalid file format. Required headers: ' . implode(', ', $expectedHeaders));
            }

            fclose($file);

            Excel::import($import = new SecondAssetImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('second_assets.index')->with('success', 'Second assets imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
