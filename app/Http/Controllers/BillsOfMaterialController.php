<?php

namespace App\Http\Controllers;

use App\Exports\BillsOfMaterialExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\BillsOfMaterialRequest;
use App\Http\Requests\UpdateBillsOfMaterialRequest;
use App\Models\BillsOfMaterial;
use App\Queries\BillsOfMaterialDataTable;
use App\Repositories\BillsOfMaterialRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class BillsOfMaterialController extends AppBaseController
{
    private $billsOfMaterialRepository;

    public function __construct(BillsOfMaterialRepository $billsOfMaterialRepo)
    {
        $this->billsOfMaterialRepository = $billsOfMaterialRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new BillsOfMaterialDataTable())->get())->make(true);
        }
        return view('bills_of_materials.index');
    }

    public function create()
    {
        return view('bills_of_materials.create');
    }

    public function store(BillsOfMaterialRequest $request)
    {
        $input = $request->all();
        try {
            $bom = $this->billsOfMaterialRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($bom)
                ->useLog('BOM created.')
                ->log($bom->BOM_code . ' BOM Created');
            return $this->sendResponse($bom, __('messages.bills_of_materials.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function show(BillsOfMaterial $billsOfMaterial)
    {
        return view('bills_of_materials.view', compact('billsOfMaterial'));
    }

    public function edit(BillsOfMaterial $billsOfMaterial)
    {
        return view('bills_of_materials.edit', compact('billsOfMaterial'));
    }

    public function update(BillsOfMaterial $billsOfMaterial, UpdateBillsOfMaterialRequest $request)
    {
        $input = $request->all();
        $bom = $this->billsOfMaterialRepository->update($input, $billsOfMaterial->id);
        activity()->performedOn($bom)->causedBy(getLoggedInUser())
            ->useLog('BOM Updated')->log($bom->BOM_code . ' BOM updated.');
        return $this->sendSuccess(__('messages.bills_of_materials.saved'));
    }

    public function destroy(BillsOfMaterial $billsOfMaterial)
    {
        try {
            $billsOfMaterial->delete();
            activity()->performedOn($billsOfMaterial)->causedBy(getLoggedInUser())
                ->useLog('BOM deleted.')->log($billsOfMaterial->BOM_code . ' BOM deleted.');
            return $this->sendSuccess(__('messages.bills_of_materials.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'boms_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new BillsOfMaterialExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $boms = BillsOfMaterial::all();
            $pdf = PDF::loadView('bills_of_materials.exports.boms_pdf', compact('boms'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new BillsOfMaterialExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $boms = BillsOfMaterial::orderBy('created_at', 'desc')->get();
            return view('bills_of_materials.exports.boms_print', compact('boms'));
        }

        abort(404);
    }
}
