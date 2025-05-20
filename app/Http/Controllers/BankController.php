<?php

namespace App\Http\Controllers;

use App\Exports\BanksExport;
use App\Queries\BankDataTable;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Exception;
use App\Http\Requests\LeaveRequest;
use App\Models\Leave;
use App\Http\Requests\UpdateBankRequest;
use App\Imports\BankImport;
use App\Repositories\BankRepository;
use App\Repositories\OverTimeRepository;
use Illuminate\Database\QueryException;
use Laracasts\Flash\Flash;
use App\Models\Bank;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class BankController extends AppBaseController
{
    /**
     * @var BankRepository;
     */
    private $bankRepository;
    public function __construct(BankRepository $bankRepo)
    {
        $this->bankRepository = $bankRepo;
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
            return DataTables::of((new BankDataTable())->get($request->all()))->make(true);
        }
        return view('banks.index');
    }

    public function create()
    {
        return view('banks.create');
    }

    public function store(LeaveRequest $request)
    {
        $input = $request->all();
        try {
            $designation = $this->bankRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($designation)
                ->useLog('Bank created.')
                ->log($designation->name);
            Flash::success(__('messages.banks.saved'));
            return $this->sendResponse($designation, __('messages.banks.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function destroy(Bank $bank)
    {

        try {
            $bank->delete();
            activity()->performedOn($bank)->causedBy(getLoggedInUser())
                ->useLog('Bank deleted.')->log($bank->name . 'Bank deleted.');
            return $this->sendSuccess(__('messages.banks.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function edit(Bank $bank)
    {

        return view('banks.edit', compact(['bank']));
    }
    public function update(Bank $bank, UpdateBankRequest $updateBankRequest)
    {
        $input = $updateBankRequest->all();
        $designation = $this->bankRepository->update($input, $updateBankRequest->id);
        activity()->performedOn($designation)->causedBy(getLoggedInUser())
            ->useLog('Bank Updated')->log($designation->name . 'Bank updated.');
        Flash::success(__('messages.banks.saved'));
        return $this->sendSuccess(__('messages.banks.saved'));
    }

    public function view(Bank $bank)
    {

        return view('banks.view', compact(['bank']));
    }

    public function export($format)
    {
        $fileName = 'banks_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new BanksExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $banks = Bank::orderBy('name')->get();
            $pdf = Pdf::loadView('banks.exports.banks_pdf', compact('banks'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new BanksExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $banks = Bank::orderBy('name')->get();
            return view('banks.exports.banks_print', compact('banks'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=banks_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'name',
            'account_number',
            'branch_name',
            'swift_code',
            'description',
            'opening_balance',
            'iban_number',
            'address'
        ];

        $rows = [
            [
                'National Bank',
                '987654321',
                'Main Branch',
                'NBANKUS33',
                'Primary business account',
                '50000.00',
                'US33NBANK987654321',
                '123 Main St, New York'
            ],
            [
                'International Bank',
                '123456789',
                'Downtown Branch',
                'INTLBKUS44',
                'Secondary account for international transactions',
                '25000.00',
                'US44INTLBK123456789',
                '456 Broadway, New York'
            ],
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        try {
            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');
            $headers = fgetcsv($file);

            $expectedHeaders = [
                'name',
                'account_number',
                'branch_name',
                'swift_code',
                'description',
                'opening_balance',
                'iban_number',
                'address'
            ];

            if (array_diff($expectedHeaders, array_map('strtolower', $headers))) {
                fclose($file);
                return redirect()->back()->with('error', 'Invalid file format. Please download the sample CSV for the correct format.');
            }

            fclose($file);

            Excel::import($import = new BankImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('banks.index')->with('success', 'Banks imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
