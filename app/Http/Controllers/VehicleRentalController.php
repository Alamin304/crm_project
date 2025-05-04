<?php

namespace App\Http\Controllers;

use App\Queries\VehicleRentalDataTable;
use App\Models\Asset;
use App\Repositories\VehicleRentalRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\VehicleRentalRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UpdateVehicleRentalRequest;
use Laracasts\Flash\Flash;
use Throwable;
use App\Models\VehicleRental;
use App\Models\DocumentNextNumber;

class VehicleRentalController extends AppBaseController
{
    /**
     * @var VehicleRentalRepository
     *
     */
    private $vehicleRentalRepository;
    public function __construct(VehicleRentalRepository $vehicleRentalRepo)
    {
        $this->vehicleRentalRepository = $vehicleRentalRepo;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new VehicleRentalDataTable())->get($request->all()))->make(true);
        }
        $usersBranches = $this->getUsersBranches();

        $accounts = $this->vehicleRentalRepository->getAccounts();

        return view('vehicle-rentals.index', compact('usersBranches', 'accounts'));
    }


    public function create()
    {
        $types = $this->vehicleRentalRepository->getTypes();
        $nextNumber = DocumentNextNumber::getNextNumber('vehicle_rental');

        return view('vehicle-rentals.create', compact('types', 'nextNumber'));
    }

    public function store(VehicleRentalRequest $request)
    {

        try {
            $assetStatus = $this->vehicleRentalRepository->saveRental($request->all());
           
            activity()->causedBy(getLoggedInUser())
                ->performedOn($assetStatus)
                ->useLog('Vehicle Rental created.')
                ->log($assetStatus->name . ' Vehicle Rental.');
            DocumentNextNumber::updateNumber('vehicle_rental');
            Flash::success(__('messages.vehicle-rentals.saved'));
            return  $this->sendResponse($assetStatus, __('messages.vehicle-rentals.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }
    public function destroy(VehicleRental $rental)
    {

        $rental->delete();
        activity()->performedOn($rental)->causedBy(getLoggedInUser())
            ->useLog('Vehicle Rental.')->log($rental->name . 'Vehicle Rental');
        return $this->sendSuccess('Asset deleted successfully.');
    }

    public function edit(VehicleRental $rental)
    {
        $types = $this->vehicleRentalRepository->getTypes();
        return view('vehicle-rentals.edit', compact(['rental', 'types']));
    }

    public function update(VehicleRental $rental, UpdateVehicleRentalRequest $updateVehicleRentalRequest)
    {
        $input = $updateVehicleRentalRequest->all();

        $updateStatus = $this->vehicleRentalRepository->updateRental($input, $rental->id);
        activity()->causedBy(getLoggedInUser())
            ->performedOn($updateStatus)
            ->useLog('Vehicle Rental.')
            ->log($updateStatus->name . ' Vehicle Rental.');
        Flash::success(__('messages.vehicle-rentals.saved'));
        return  $this->sendResponse($updateStatus, __('messages.vehicle-rentals.saved'));
    }
    public function view(VehicleRental $rental)
    {

        return view('vehicle-rentals.view', compact(['rental']));
    }
    public function updatePayment(VehicleRental $rental, Request $request)
    {
        $validatedData = $request->validate([
            'branch_id' => 'required|integer',
            'account_id' => 'required|integer',
            'paid_amount' => 'required|numeric',
        ]);

        $updatedRental = $this->vehicleRentalRepository->updatePayment($rental, $validatedData);

        activity()->causedBy(getLoggedInUser())
            ->performedOn($updatedRental)
            ->useLog('Vehicle Rental.')
            ->log($updatedRental->name . ' Vehicle Rental.');
        Flash::success(__('messages.vehicle-rentals.saved'));
        return  $this->sendResponse($updatedRental, __('messages.vehicle-rentals.saved'));
    }
}
