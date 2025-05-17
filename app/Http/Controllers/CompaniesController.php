<?php

namespace App\Http\Controllers;

use App\Exports\CompaniesExport;
use App\Http\Requests\CompaniesRequest;
use App\Http\Requests\UpdateCompaniesRequest;
use App\Models\Company;
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
            return DataTables::of((new CompaniesDataTable())->get())
                ->addIndexColumn()
                ->make(true);
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
            $Company = $this->companieRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($Company)
                ->useLog('Company created.')
                ->log($Company->name . ' Division Created');
            return $this->sendResponse($Company, __('messages.companies.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(Company $Company)
    {
        return view('companies.view', compact('Company'));
    }

    public function edit(Company $Company)
    {
        return view('companies.edit', compact('Company'));
    }

    public function update(Company $Company, UpdateCompaniesRequest $request)
    {
        $input = $request->all();
        $this->companieRepository->update($input, $Company->id);
        activity()->performedOn($Company)->causedBy(getLoggedInUser())
            ->useLog('Company Updated')->log($Company->name . ' Company updated.');
        return $this->sendSuccess(__('messages.companies.saved'));
    }

    public function destroy(Company $Company)
    {
        try {
            $Company->delete();
            activity()->performedOn($Company)->causedBy(getLoggedInUser())
                ->useLog('Company deleted.')->log($Company->name . ' Company deleted.');
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
            $companies = Company::all();
            $pdf = Pdf::loadView('companies.exports.companies_pdf', compact('companies'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new CompaniesExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $companies = Company::orderBy('name', 'asc')->get();
            return view('companies.exports.companies_print', compact('companies'));
        }

        abort(404);
    }
}
