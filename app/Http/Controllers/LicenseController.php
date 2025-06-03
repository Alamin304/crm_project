<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Http\Requests\LicenseRequest;
use App\Http\Requests\UpdateLicenseRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LicensesExport;
use App\Imports\LicenseImport;
use App\Queries\LicenseDataTable;
use App\Repositories\LicenseRepository;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LicenseController extends AppBaseController
{
    private $licenseRepository;

    public function __construct(LicenseRepository $licenseRepo)
    {
        $this->licenseRepository = $licenseRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new LicenseDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('licenses.index');
    }

    public function create()
    {
        $categories = ['Operating System', 'Office Suite', 'Design Software', 'Development Tool', 'Security Software'];
        $manufacturers = ['Microsoft', 'Adobe', 'Autodesk', 'Oracle', 'JetBrains'];
        $suppliers = ['Tech Supplier Inc', 'Software World', 'Digital Solutions', 'Global Tech', 'SoftServe'];
        $depreciations = ['3 Years', '5 Years', '7 Years', '10 Years', 'None'];
        
        return view('licenses.create', compact('categories', 'manufacturers', 'suppliers', 'depreciations'));
    }

    public function store(LicenseRequest $request)
    {
        $input = $request->all();
        try {
            $license = $this->licenseRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($license)
                ->useLog('License created.')
                ->log($license->software_name . ' License Created');
            return $this->sendResponse($license, __('messages.licenses.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(License $license)
    {
        return view('licenses.view', compact('license'));
    }

    public function edit(License $license)
    {
        $categories = ['Operating System', 'Office Suite', 'Design Software', 'Development Tool', 'Security Software'];
        $manufacturers = ['Microsoft', 'Adobe', 'Autodesk', 'Oracle', 'JetBrains'];
        $suppliers = ['Tech Supplier Inc', 'Software World', 'Digital Solutions', 'Global Tech', 'SoftServe'];
        $depreciations = ['3 Years', '5 Years', '7 Years', '10 Years', 'None'];
        
        return view('licenses.edit', compact('license', 'categories', 'manufacturers', 'suppliers', 'depreciations'));
    }

    public function update(License $license, UpdateLicenseRequest $request)
    {
        $input = $request->all();
        $this->licenseRepository->update($input, $license->id);
        activity()->performedOn($license)->causedBy(getLoggedInUser())
            ->useLog('License Updated')->log($license->software_name . ' License updated.');
        return $this->sendSuccess(__('messages.licenses.saved'));
    }

    public function destroy(License $license)
    {
        try {
            $license->delete();
            activity()->performedOn($license)->causedBy(getLoggedInUser())
                ->useLog('License deleted.')->log($license->software_name . ' License deleted.');
            return $this->sendSuccess(__('messages.licenses.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'licenses_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new LicensesExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $licenses = License::all();
            $pdf = PDF::loadView('licenses.exports.licenses_pdf', compact('licenses'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new LicensesExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $licenses = License::orderBy('software_name', 'asc')->get();
            return view('licenses.exports.licenses_print', compact('licenses'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=licenses_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'software_name', 'category_name', 'product_key', 'seats', 'manufacturer', 
            'licensed_name', 'licensed_email', 'reassignable', 'supplier', 'order_number',
            'purchase_order_number', 'purchase_cost', 'purchase_date', 'expiration_date',
            'termination_date', 'depreciation', 'maintained', 'for_sell', 'selling_price', 'notes'
        ];
        
        $rows = [
            [
                'Windows 10 Pro', 'Operating System', 'ABCDE-12345-FGHIJ', 5, 'Microsoft',
                'John Doe', 'john@example.com', 1, 'Tech Supplier Inc', 'ORD123',
                'PO456', 199.99, '2023-01-15', '2026-01-15', null, '5 Years', 1, 0, null, 'Primary license for office computers'
            ],
            [
                'Adobe Photoshop', 'Design Software', 'ZXCVB-67890-QWERT', 2, 'Adobe',
                'Jane Smith', 'jane@example.com', 0, 'Software World', 'ORD456',
                'PO789', 599.99, '2023-02-20', '2024-02-20', null, '3 Years', 1, 1, 699.99, 'For design team only'
            ]
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

        try {
            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');
            $headers = fgetcsv($file);

            $expectedHeaders = [
                'software_name', 'category_name', 'product_key', 'seats', 'manufacturer', 
                'licensed_name', 'licensed_email', 'reassignable', 'supplier', 'order_number',
                'purchase_order_number', 'purchase_cost', 'purchase_date', 'expiration_date',
                'termination_date', 'depreciation', 'maintained', 'for_sell', 'selling_price', 'notes'
            ];

            if (array_map('strtolower', $headers) !== array_map('strtolower', $expectedHeaders)) {
                return redirect()->back()->with('error', 'Invalid file format. Please download the sample file for correct format.');
            }

            fclose($file);

            Excel::import($import = new LicenseImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('licenses.index')->with('success', 'Licenses imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}