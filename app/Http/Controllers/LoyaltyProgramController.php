<?php

namespace App\Http\Controllers;

use App\Exports\LoyaltyProgramsExport;
use App\Http\Requests\CreateLoyaltyProgramRequest;
use App\Http\Requests\UpdateLoyaltyProgramRequest;
use App\Imports\LoyaltyProgramsImport;
use App\Models\LoyaltyProgram;
use App\Queries\LoyaltyProgramDataTable;
use App\Repositories\LoyaltyProgramRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class LoyaltyProgramController extends AppBaseController
{
    private $loyaltyProgramRepository;

    public function __construct(LoyaltyProgramRepository $loyaltyProgramRepo)
    {
        $this->loyaltyProgramRepository = $loyaltyProgramRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new LoyaltyProgramDataTable())->get())->make(true);
        }
        return view('loyalty_programs.index');
    }

    public function create()
    {
        return view('loyalty_programs.create');
    }

    public function store(CreateLoyaltyProgramRequest $request)
    {
        $input = $request->all();

        // Process dynamic rules
        $rules = [];
        if ($request->has('rule_name')) {
            foreach ($request->rule_name as $key => $value) {
                $rules[] = [
                    'rule_name' => $value,
                    'point_from' => $request->point_from[$key],
                    'point_to' => $request->point_to[$key],
                    'point_weight' => $request->point_weight[$key],
                    'status' => $request->rule_status[$key] ?? 'enabled'
                ];
            }
        }
        $input['rules'] = $rules;

        try {
            $loyaltyProgram = $this->loyaltyProgramRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($loyaltyProgram)
                ->useLog('Loyalty Program created.')
                ->log($loyaltyProgram->name . ' Loyalty Program Created');
            return $this->sendResponse($loyaltyProgram, __('messages.loyalty_programs.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(LoyaltyProgram $loyaltyProgram)
    {
        return view('loyalty_programs.view', compact('loyaltyProgram'));
    }

    public function edit(LoyaltyProgram $loyaltyProgram)
    {
        return view('loyalty_programs.edit', compact('loyaltyProgram'));
    }

    public function update(LoyaltyProgram $loyaltyProgram, UpdateLoyaltyProgramRequest $request)
    {
        $input = $request->all();

        // Process dynamic rules
        $rules = [];
        if ($request->has('rule_name')) {
            foreach ($request->rule_name as $key => $value) {
                $rules[] = [
                    'rule_name' => $value,
                    'point_from' => $request->point_from[$key],
                    'point_to' => $request->point_to[$key],
                    'point_weight' => $request->point_weight[$key],
                    'status' => $request->rule_status[$key] ?? 'enabled'
                ];
            }
        }
        $input['rules'] = $rules;

        $loyaltyProgram = $this->loyaltyProgramRepository->update($input, $loyaltyProgram->id);
        activity()->performedOn($loyaltyProgram)->causedBy(getLoggedInUser())
            ->useLog('Loyalty Program Updated')->log($loyaltyProgram->name . ' Loyalty Program updated.');
        return $this->sendSuccess(__('messages.loyalty_programs.saved'));
    }

    public function destroy(LoyaltyProgram $loyaltyProgram)
    {
        try {
            $loyaltyProgram->delete();
            activity()->performedOn($loyaltyProgram)->causedBy(getLoggedInUser())
                ->useLog('Loyalty Program deleted.')->log($loyaltyProgram->name . ' Loyalty Program deleted.');
            return $this->sendSuccess(__('messages.loyalty_programs.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'loyalty_programs_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new LoyaltyProgramsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $loyaltyPrograms = LoyaltyProgram::all();
            $pdf = Pdf::loadView('loyalty_programs.exports.loyalty_programs_pdf', compact('loyaltyPrograms'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new LoyaltyProgramsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $loyaltyPrograms = LoyaltyProgram::orderBy('created_at', 'desc')->get();
            return view('loyalty_programs.exports.loyalty_programs_print', compact('loyaltyPrograms'));
        }

        abort(404);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        try {
            Excel::import(new LoyaltyProgramsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Loyalty programs imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return redirect()->back()->with('failures', $failures);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    public function sampleCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="loyalty_programs_sample.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'name',
                'customer_group',
                'customer',
                'start_date',
                'end_date',
                'description',
                'rule_base',
                'minimum_purchase',
                'account_creation_point',
                'birthday_point',
                'redeem_type',
                'minimum_point_to_redeem',
                'max_amount_receive',
                'redeem_in_portal',
                'redeem_in_pos',
                'status'
            ]);

            // Add sample data
            fputcsv($file, [
                'Gold Member Program',
                'gold',
                'existing',
                '2023-01-01',
                '2023-12-31',
                'Premium loyalty program for gold members',
                'purchase_amount',
                '100.00',
                '500',
                '200',
                'fixed_amount',
                '1000',
                '100.00',
                '1',
                '1',
                'enabled'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
