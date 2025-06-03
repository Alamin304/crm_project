<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAssetRequest;
use App\Http\Requests\CreateSecondAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Http\Requests\UpdateSecondAssetRequest;
use App\Models\SecondAsset;
use App\Repositories\SecondAssetRepository;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Response;
use Yajra\DataTables\Facades\DataTables;

class SecondAssetController extends AppBaseController
{
    private $assetRepository;

    public function __construct(SecondAssetRepository $assetRepo)
    {
        $this->assetRepository = $assetRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(SecondAsset::query())
                ->addIndexColumn()
                ->addColumn('action', function ($asset) {
                    return view('assets.action', compact('asset'))->render();
                })
                ->editColumn('status', function ($asset) {
                    return ucfirst($asset->status);
                })
                ->editColumn('purchase_cost', function ($asset) {
                    return formatCurrency($asset->purchase_cost);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('second_assets.index');
    }

    public function create()
    {
        $statusOptions = SecondAsset::statusOptions();
        $modelOptions = SecondAsset::modelOptions();
        $supplierOptions = SecondAsset::supplierOptions();
        $locationOptions = SecondAsset::locationOptions();
        $rentalUnitOptions = SecondAsset::rentalUnitOptions();

        return view('second_assets.create', compact(
            'statusOptions',
            'modelOptions',
            'supplierOptions',
            'locationOptions',
            'rentalUnitOptions'
        ));
    }

    public function store(CreateSecondAssetRequest $request)
    {
        $input = $request->all();

        $input['requestable'] = $request->has('requestable');
        $input['for_sale'] = $request->has('for_sale');
        $input['for_rent'] = $request->has('for_rent');

        $asset = $this->assetRepository->create($input);

        activity()->causedBy(getLoggedInUser())
            ->performedOn($asset)
            ->useLog('Asset created.')
            ->log($asset->asset_name . ' Asset Created');

        return $this->sendResponse($asset, __('messages.second_assets.saved'));
    }

    public function show($id)
    {
        $asset = $this->assetRepository->find($id);

        if (empty($asset)) {
            return $this->sendError('Asset not found');
        }

        return view('second_assets.show')->with('asset', $asset);
    }

    public function edit($id)
    {
        $asset = $this->assetRepository->find($id);

        if (empty($asset)) {
            return $this->sendError('Asset not found');
        }

        $statusOptions = SecondAsset::statusOptions();
        $modelOptions = SecondAsset::modelOptions();
        $supplierOptions = SecondAsset::supplierOptions();
        $locationOptions = SecondAsset::locationOptions();
        $rentalUnitOptions = SecondAsset::rentalUnitOptions();

        return view('second_assets.edit', compact(
            'asset',
            'statusOptions',
            'modelOptions',
            'supplierOptions',
            'locationOptions',
            'rentalUnitOptions'
        ));
    }

    public function update($id, UpdateSecondAssetRequest $request)
    {
        $asset = $this->assetRepository->find($id);

        if (empty($asset)) {
            return $this->sendError('Asset not found');
        }

        $input = $request->all();

        $input['requestable'] = $request->has('requestable');
        $input['for_sale'] = $request->has('for_sale');
        $input['for_rent'] = $request->has('for_rent');

        $asset = $this->assetRepository->update($input, $id);

        activity()->performedOn($asset)->causedBy(getLoggedInUser())
            ->useLog('Asset Updated')->log($asset->asset_name . ' Asset updated.');

        return $this->sendSuccess(__('messages.assets.updated'));
    }

    public function destroy($id)
    {
        $asset = $this->assetRepository->find($id);

        if (empty($asset)) {
            return $this->sendError('Asset not found');
        }

        try {
            $asset->delete();

            activity()->performedOn($asset)->causedBy(getLoggedInUser())
                ->useLog('Asset deleted.')->log($asset->asset_name . ' Asset deleted.');

            return $this->sendSuccess(__('messages.assets.deleted'));
        } catch (QueryException $e) {
            return $this->sendError('Failed to delete! Asset is in use.');
        }
    }

    public function generateSerialNumber()
    {
        $prefix = 'AST';
        $latest = SecondAsset::orderBy('id', 'desc')->first();
        $number = $latest ? (int) substr($latest->serial_number, 3) + 1 : 1;
        $serialNumber = $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);

        return $this->sendResponse($serialNumber, 'Serial number generated');
    }
}
