<?php

// app/Http/Controllers/UnacceptedAssetController.php

namespace App\Http\Controllers;

use App\Exports\UnacceptedAssetsExport;
use App\Http\Controllers\AppBaseController;
use App\Models\UnacceptedAsset;
use App\Queries\UnacceptedAssetDataTable;
use App\Repositories\UnacceptedAssetRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class UnacceptedAssetController extends AppBaseController
{
    private $unacceptedAssetRepository;

    public function __construct(UnacceptedAssetRepository $unacceptedAssetRepo)
    {
        $this->unacceptedAssetRepository = $unacceptedAssetRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new UnacceptedAssetDataTable())->get())->make(true);
        }
        return view('unaccepted_assets.index');
    }

    public function destroy($id)
    {
        try {
            $asset = $this->unacceptedAssetRepository->find($id);
            $asset->delete();

            return $this->sendSuccess(__('messages.unaccepted_asset.deleted_successfully'));
        } catch (\Exception $e) {
            return $this->sendError(__('messages.unaccepted_asset.not_found'), 404);
        }
    }

    public function export($format)
    {
        $fileName = 'unaccepted_assets_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new UnacceptedAssetsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $assets = UnacceptedAsset::all();
            $pdf = Pdf::loadView('unaccepted_assets.exports.unaccepted_assets_pdf', compact('assets'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new UnacceptedAssetsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $assets = UnacceptedAsset::orderBy('created_at', 'desc')->get();
            return view('unaccepted_assets.exports.unaccepted_assets_print', compact('assets'));
        }

        abort(404);
    }
}
