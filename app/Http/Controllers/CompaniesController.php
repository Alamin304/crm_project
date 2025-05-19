<?php

namespace App\Http\Controllers;

use App\Exports\CompaniesExport;
use App\Http\Requests\CompaniesRequest;
use App\Http\Requests\UpdateCompaniesRequest;
use App\Imports\CompaniesImport;
use App\Models\Company;
use App\Queries\CompaniesDataTable;
use App\Repositories\CompanieRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
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

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=companies_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['name', 'description'];
        $rows = [
            ['Company A', 'This is a sample company.'],
            ['Company B', 'This is another sample.'],
        ];

        $callback = function () use ($columns, $rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function importCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt,xlsx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Prevent duplicate import if companies already exist
        if (\App\Models\Company::exists()) {
            return redirect()->back()->with('error', 'Import failed: Companies already exist in the database.');
        }

        try {
            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');
            $headers = fgetcsv($file);

            $expectedHeaders = ['name', 'description'];

            if (array_map('strtolower', $headers) !== $expectedHeaders) {
                return redirect()->back()->with('error', 'Invalid file format. Required headers: name, description.');
            }

            fclose($file);

            Excel::import($import = new CompaniesImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('companies.index')->with('success', 'Companies imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }

}
