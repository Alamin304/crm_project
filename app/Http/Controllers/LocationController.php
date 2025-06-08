<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\Location;
use App\Queries\LocationDataTable;
use App\Repositories\LocationRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends AppBaseController
{
    private $locationRepository;

    public function __construct(LocationRepository $locationRepo)
    {
        $this->locationRepository = $locationRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new LocationDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('locations.index');
    }

    public function create()
    {
        $parentOptions = ['Headquarters', 'Branch Office', 'Warehouse', 'Retail Store'];
        $managerOptions = ['John Doe', 'Jane Smith', 'Robert Johnson', 'Emily Davis'];
        $currencyOptions = ['USD', 'EUR', 'GBP', 'JPY', 'CAD'];

        return view('locations.create', compact('parentOptions', 'managerOptions', 'currencyOptions'));
    }

    public function store(LocationRequest $request)
    {
        $input = $request->all();

        try {
            if ($request->hasFile('image')) {
                $input['image'] = $request->file('image')->store('locations', 'public');
            }

            $location = $this->locationRepository->create($input);

            activity()->causedBy(getLoggedInUser())
                ->performedOn($location)
                ->useLog('Location created.')
                ->log($location->location_name . ' Location Created');

            return $this->sendResponse($location, __('messages.location.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        $location = Location::findOrFail($id);
        return view('locations.view', compact('location'));
    }

    public function edit($id)
    {
        $location = Location::findOrFail($id);
        $parentOptions = ['Headquarters', 'Branch Office', 'Warehouse', 'Retail Store'];
        $managerOptions = ['John Doe', 'Jane Smith', 'Robert Johnson', 'Emily Davis'];
        $currencyOptions = ['USD', 'EUR', 'GBP', 'JPY', 'CAD'];

        return view('locations.edit', compact('location', 'parentOptions', 'managerOptions', 'currencyOptions'));
    }

    public function update(LocationRequest $request, $id)
    {
        $input = $request->all();

        try {
            $location = Location::findOrFail($id);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($location->image) {
                    Storage::disk('public')->delete($location->image);
                }
                $input['image'] = $request->file('image')->store('locations', 'public');
            }

            $location = $this->locationRepository->update($input, $id);

            activity()->causedBy(getLoggedInUser())
                ->performedOn($location)
                ->useLog('Location updated.')
                ->log($location->location_name . ' Location updated');

            return $this->sendResponse($location, __('messages.location.updated'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            $location = Location::findOrFail($id);

            // Delete image if exists
            if ($location->image) {
                Storage::disk('public')->delete($location->image);
            }

            $location->delete();

            activity()->performedOn($location)->causedBy(getLoggedInUser())
                ->useLog('Location deleted.')->log($location->location_name . ' Location deleted.');

            return $this->sendSuccess(__('messages.location.delete'));
        } catch (Exception $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }
}
