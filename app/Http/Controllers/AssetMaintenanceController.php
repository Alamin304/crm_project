<?php

namespace App\Http\Controllers;

use App\Exports\AssetMaintenancesExport;
use App\Http\Requests\AssetMaintenanceRequest;
use App\Http\Requests\UpdateAssetMaintenanceRequest;
use App\Models\AssetMaintenance;
use App\Queries\AssetMaintenanceDataTable;
use App\Repositories\AssetMaintenanceRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class AssetMaintenanceController extends AppBaseController
{
    /** @var AssetMaintenanceRepository */
    private $assetMaintenanceRepository;

    public function __construct(AssetMaintenanceRepository $assetMaintenanceRepo)
    {
        $this->assetMaintenanceRepository = $assetMaintenanceRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new AssetMaintenanceDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('asset_maintenances.index');
    }

    public function create()
    {
        $assets = \App\Models\Asset::pluck('name', 'id')->toArray();
        // $suppliers = \App\Models\Supplier::pluck('name', 'id')->toArray();
        $maintenanceTypes = [
            'Preventive' => 'Preventive',
            'Corrective' => 'Corrective',
            'Predictive' => 'Predictive',
            'Breakdown' => 'Breakdown'
        ];

        return view('asset_maintenances.create', compact('assets', 'maintenanceTypes'));
    }

    public function store(AssetMaintenanceRequest $request)
    {
        $input = $request->all();
        $input['warranty_improvement'] = isset($input['warranty_improvement']);

        try {
            $assetMaintenance = $this->assetMaintenanceRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($assetMaintenance)
                ->useLog('Asset Maintenance created.')
                ->log($assetMaintenance->title . ' Asset Maintenance Created');
            return $this->sendResponse($assetMaintenance, __('messages.asset_maintenance.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function show(AssetMaintenance $assetMaintenance)
    {
        return view('asset_maintenances.view', compact('assetMaintenance'));
    }

    public function edit(AssetMaintenance $assetMaintenance)
    {
        $assets = \App\Models\Asset::pluck('name', 'id')->toArray();
        // $suppliers = \App\Models\Supplier::pluck('name', 'id')->toArray();
        $maintenanceTypes = [
            'Preventive' => 'Preventive',
            'Corrective' => 'Corrective',
            'Predictive' => 'Predictive',
            'Breakdown' => 'Breakdown'
        ];

        return view('asset_maintenances.edit', compact('assetMaintenance', 'assets', 'maintenanceTypes'));
    }

    public function update(AssetMaintenance $assetMaintenance, UpdateAssetMaintenanceRequest $request)
    {
        $input = $request->all();
        $input['warranty_improvement'] = isset($input['warranty_improvement']);

        $this->assetMaintenanceRepository->update($input, $assetMaintenance->id);
        activity()->performedOn($assetMaintenance)->causedBy(getLoggedInUser())
            ->useLog('Asset Maintenance Updated')->log($assetMaintenance->title . ' Asset Maintenance updated.');
        return $this->sendSuccess(__('messages.asset_maintenance.saved'));
    }

    public function destroy(AssetMaintenance $assetMaintenance)
    {
        try {
            $assetMaintenance->delete();
            activity()->performedOn($assetMaintenance)->causedBy(getLoggedInUser())
                ->useLog('Asset Maintenance deleted.')->log($assetMaintenance->title . ' Asset Maintenance deleted.');
            return $this->sendSuccess(__('messages.asset_maintenance.delete'));
        } catch (Exception $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'asset_maintenances_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new AssetMaintenancesExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $assetMaintenances = AssetMaintenance::all();
            $pdf = Pdf::loadView('asset_maintenances.exports.asset_maintenances_pdf', compact('assetMaintenances'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new AssetMaintenancesExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $assetMaintenances = AssetMaintenance::orderBy('title', 'asc')->get();
            return view('asset_maintenances.exports.asset_maintenances_print', compact('assetMaintenances'));
        }

        abort(404);
    }
}
