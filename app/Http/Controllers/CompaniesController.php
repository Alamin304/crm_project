<?php

namespace App\Http\Controllers;

use App\Exports\CompaniesExport;
use App\Http\Requests\CompaniesRequest;
use App\Http\Requests\UpdateCompaniesRequest;
use App\Models\Companie;
use App\Queries\CompaniesDataTable;
use App\Repositories\CompanieRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CompaniesController extends AppBaseController
{
   private $companieRepository;

    public function __construct(CompanieRepository $divisionRepo)
    {
        $this->companieRepository = $divisionRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new CompaniesDataTable())->get())->make(true);
        }
        return view('companies.index');
    }

    public function create()
    {
        return view('companies.create');
    }

   public function store(CompaniesRequest $request)
    {
        $input = $request->all();
        try {
            $companie = $this->companieRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($companie)
                ->useLog('Companie created.')
                ->log($companie->name . ' Division Created');
            return $this->sendResponse($companie, __('messages.companies.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(Companie $companie)
    {
        return view('companies.view', compact('companie'));
    }

    public function edit(Companie $companie)
    {
        return view('companies.edit', compact('companie'));
    }

    public function update(Companie $companie, UpdateCompaniesRequest $request)
    {
        $input = $request->all();
        $this->companieRepository->update($input, $companie->id);
        activity()->performedOn($companie)->causedBy(getLoggedInUser())
            ->useLog('Companie Updated')->log($companie->name . ' Companie updated.');
        return $this->sendSuccess(__('messages.companies.saved'));
    }

    public function destroy(Companie $companie)
    {
        try {
            $companie->delete();
            activity()->performedOn($companie)->causedBy(getLoggedInUser())
                ->useLog('Companie deleted.')->log($companie->name . ' Companie deleted.');
            return $this->sendSuccess(__('messages.companies.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'companies_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new CompaniesExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $companies = Companie::all();
            $pdf = Pdf::loadView('companies.exports.companies_pdf', compact('companies'));
            return $pdf->download($fileName);
        }

        abort(404);
    }

}

