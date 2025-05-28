<?php

namespace App\Http\Controllers;

use App\Exports\PropertyOwnerExport;
use App\Http\Requests\PropertyOwnerRequest;
use App\Http\Requests\UpdatePropertyOwnerRequest;
use App\Models\PropertyOwner;
use App\Queries\PropertyOwnerDataTable;
use App\Repositories\PropertyOwnerRepository;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class PropertyOwnerController extends AppBaseController
{
    private $propertyOwnerRepository;

    public function __construct(PropertyOwnerRepository $repo)
    {
        $this->propertyOwnerRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return FacadesDataTables::of((new PropertyOwnerDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }

        return view('property_owners.index');
    }

    public function create()
    {
        return view('property_owners.create');
    }

    public function store(PropertyOwnerRequest $request)
    {
        $input = $request->all();

        // Handle file upload
        if ($request->hasFile('profile_image')) {
            $input['profile_image'] = $request->file('profile_image')->store('property_owners', 'public');
        }

        $propertyOwner = $this->propertyOwnerRepository->create($input);

        return $this->sendResponse($propertyOwner, 'Property Owner saved successfully.');
    }

    public function show(PropertyOwner $propertyOwner)
    {
        return view('property_owners.view', compact('propertyOwner'));
    }

    public function edit(PropertyOwner $propertyOwner)
    {
        return view('property_owners.edit', compact('propertyOwner'));
    }

    public function update(PropertyOwner $propertyOwner, UpdatePropertyOwnerRequest $request)
    {
        $input = $request->all();

        // Handle file upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($propertyOwner->profile_image) {
                Storage::disk('public')->delete($propertyOwner->profile_image);
            }
            $input['profile_image'] = $request->file('profile_image')->store('property_owners', 'public');
        }

        $this->propertyOwnerRepository->update($input, $propertyOwner->id);

        return $this->sendSuccess('Property Owner updated successfully.');
    }

    public function destroy(PropertyOwner $propertyOwner)
    {
        // Delete image if exists
        if ($propertyOwner->profile_image) {
            Storage::disk('public')->delete($propertyOwner->profile_image);
        }

        $propertyOwner->delete();
        return $this->sendSuccess('Property Owner deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'property_owners_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new PropertyOwnerExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $propertyOwners = PropertyOwner::all();
            $pdf = Pdf::loadView('property_owners.exports.property_owners_pdf', compact('propertyOwners'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new PropertyOwnerExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $propertyOwners = PropertyOwner::orderBy('created_at', 'desc')->get();
            return view('property_owners.exports.property_owners_print', compact('propertyOwners'));
        }

        abort(404);
    }

    public function updateStatus(Request $request, PropertyOwner $propertyOwner)
    {
        $propertyOwner->is_active = $request->is_active;
        $propertyOwner->save();

        return response()->json([
            'success' => true,
            'message' => 'Property Owner status updated successfully.',
        ]);
    }
}
