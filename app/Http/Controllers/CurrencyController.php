<?php

namespace App\Http\Controllers;

use App\Exports\CurrenciesExport;
use App\Queries\CurrencyDataTable;
use Illuminate\Http\Request;
use App\Repositories\CurrencyRepository;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Exception;
use App\Http\Requests\CurrencyReqeust;
use App\Models\Currency;
use App\Http\Requests\UpdateCurrencyRequest;
use App\Imports\CurrencyImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class CurrencyController extends AppBaseController
{
    /**
     * @var CurrencyRepository
     */
    private $currencyRepository;
    public function __construct(CurrencyRepository $currencyRepo)
    {
        $this->currencyRepository = $currencyRepo;
    }
    /**
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new CurrencyDataTable())->get($request->only(['group'])))->make(true);
        }
        return view('currencies.index');
    }

    public function create()
    {
        return view('currencies.create');
    }

    public function store(CurrencyReqeust $request)
    {

        $input = $request->all();
        try {
            $currency = $this->currencyRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($currency)
                ->useLog('Currency created.')
                ->log($currency->name . ' Currency Created');
            Flash::success(__('messages.currencies.saved'));
            return $this->sendResponse($currency, __('messages.currencies.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }
    public function destroy(Currency $currency)
    {
        try {
            $currency->delete();
            activity()->performedOn($currency)->causedBy(getLoggedInUser())
                ->useLog('Currency deleted.')->log($currency->name . ' Currency deleted.');
            return $this->sendSuccess(__('messages.currencies.saved'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function edit(Currency $currency)
    {
        return view('currencies.edit', compact(['currency']));
    }
    public function view(Currency $currency)
    {
        return view('currencies.view', compact(['currency']));
    }
    public function update(Currency $currency, UpdateCurrencyRequest $updateCurrencyRequest)
    {
        $input = $updateCurrencyRequest->all();
        $updateCurrency = $this->currencyRepository->update($input, $updateCurrencyRequest->id);
        activity()->performedOn($updateCurrency)->causedBy(getLoggedInUser())
            ->useLog('Currency Updated')->log($updateCurrency->name . ' Currency updated.');
        Flash::success(__('messages.currencies.saved'));
        return $this->sendSuccess(__('messages.currencies.saved'));
    }

    public function export($format)
    {
        $fileName = 'currencies_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new CurrenciesExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $currencies = Currency::orderBy('name')->get();
            $pdf = Pdf::loadView('currencies.exports.currencies_pdf', compact('currencies'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new CurrenciesExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $currencies = Currency::orderBy('name')->get();
            return view('currencies.exports.currencies_print', compact('currencies'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
{
    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=currencies_sample.csv",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $columns = ['name', 'description'];
    $rows = [
        ['USD', 'United States Dollar'],
        ['EUR', 'Euro'],
        ['JPY', 'Japanese Yen'],
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

    // Prevent duplicate import if currencies already exist
    if (\App\Models\Currency::exists()) {
        return redirect()->back()->with('error', 'Import failed: Currencies already exist in the database.');
    }

    try {
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        $headers = fgetcsv($file);

        $expectedHeaders = ['name', 'description'];

        if (array_map('strtolower', $headers) !== array_map('strtolower', $expectedHeaders)) {
            return redirect()->back()->with('error', 'Invalid file format. Required headers: name, description.');
        }

        fclose($file);

        Excel::import($import = new CurrencyImport, $request->file('file'));

        if (!empty($import->failures())) {
            return redirect()->back()->with([
                'failures' => $import->failures(),
            ]);
        }

        return redirect()->route('currencies.index')->with('success', 'Currencies imported successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
    }
}
}
