<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\Depreciation;
use App\Queries\DepreciationDataTable;
use App\Repositories\DepreciationRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\DataTables;

class DepreciationController extends AppBaseController
{
    private $depreciationRepository;

    public function __construct(DepreciationRepository $depreciationRepo)
    {
        $this->depreciationRepository = $depreciationRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new DepreciationDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('depreciations.index');
    }

    public function create()
    {
        $statuses = [
            'ready' => 'Ready',
            'pending' => 'Pending',
            'undeployable' => 'Undeployable',
            'archive' => 'Archive',
            'operational' => 'Operational',
            'non-operational' => 'Non-Operational',
            'repairing' => 'Repairing'
        ];

        $assetNames = [
            'Laptop Dell XPS 15',
            'Desktop HP Elite',
            'MacBook Pro 16"',
            'Lenovo ThinkPad',
            'Dell Monitor 24"',
            'HP Printer LaserJet',
            'Apple iPad Pro'
        ];

        return view('depreciations.create', compact('statuses', 'assetNames'));
    }

    public function store(Request $request)
    {
        $input = $request->all();

        try {
            if ($request->hasFile('image')) {
                $input['image'] = $request->file('image')->store('depreciations', 'public');
            }

            // Calculate values if needed
            $input['current_value'] = $this->calculateCurrentValue($input);
            $input['monthly_depreciation'] = $this->calculateMonthlyDepreciation($input);
            $input['remaining'] = $this->calculateRemainingValue($input);

            $depreciation = $this->depreciationRepository->create($input);

            activity()->causedBy(getLoggedInUser())
                ->performedOn($depreciation)
                ->useLog('Depreciation created.')
                ->log($depreciation->asset_name . ' Depreciation Created');

            return $this->sendResponse($depreciation, __('messages.depreciation.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function destroy(Depreciation $depreciation)
    {
        try {
            // Delete image if exists
            if ($depreciation->image) {
                Storage::disk('public')->delete($depreciation->image);
            }

            $depreciation->delete();

            activity()->performedOn($depreciation)->causedBy(getLoggedInUser())
                ->useLog('Depreciation deleted.')->log($depreciation->asset_name . ' Depreciation deleted.');

            return $this->sendSuccess(__('messages.depreciation.delete'));
        } catch (Exception $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    private function calculateCurrentValue($input)
    {
        // Implement your calculation logic here
        return $input['cost'] ?? 0;
    }

    private function calculateMonthlyDepreciation($input)
    {
        // Implement your calculation logic here
        return ($input['cost'] ?? 0) / ($input['number_of_month'] ?? 1);
    }

    private function calculateRemainingValue($input)
    {
        // Implement your calculation logic here
        return $input['cost'] ?? 0;
    }
}
