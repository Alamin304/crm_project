<?php

namespace App\Http\Controllers;

use App\Exports\MembershipRulesExport;
use App\Http\Requests\CreateMembershipRuleRequest;
use App\Http\Requests\UpdateMembershipRuleRequest;
use App\Imports\MembershipRulesImport;
use App\Models\MembershipRule;
use App\Queries\MembershipRuleDataTable;
use App\Repositories\MembershipRuleRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class MembershipRuleController extends AppBaseController
{
    /** @var MembershipRuleRepository */
    private $membershipRuleRepository;

    public function __construct(MembershipRuleRepository $membershipRuleRepo)
    {
        $this->membershipRuleRepository = $membershipRuleRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new MembershipRuleDataTable())->get())->make(true);
        }
        return view('membership_rules.index');
    }

    public function create()
    {
        return view('membership_rules.create');
    }

    public function store(CreateMembershipRuleRequest $request)
    {
        $input = $request->all();
        try {
            $membershipRule = $this->membershipRuleRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($membershipRule)
                ->useLog('Membership Rule created.')
                ->log($membershipRule->name . ' Membership Rule Created');
            return $this->sendResponse($membershipRule, __('messages.membership_rules.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(MembershipRule $membershipRule)
    {
        return view('membership_rules.view', compact('membershipRule'));
    }

    public function edit(MembershipRule $membershipRule)
    {
        return view('membership_rules.edit', compact('membershipRule'));
    }

    public function update(MembershipRule $membershipRule, UpdateMembershipRuleRequest $request)
    {
        $input = $request->all();
        $membershipRule = $this->membershipRuleRepository->update($input, $membershipRule->id);
        activity()->performedOn($membershipRule)->causedBy(getLoggedInUser())
            ->useLog('Membership Rule Updated')->log($membershipRule->name . ' Membership Rule updated.');
        return $this->sendSuccess(__('messages.membership_rules.saved'));
    }

    public function destroy(MembershipRule $membershipRule)
    {
        try {
            $membershipRule->delete();
            activity()->performedOn($membershipRule)->causedBy(getLoggedInUser())
                ->useLog('Membership Rule deleted.')->log($membershipRule->name . ' Membership Rule deleted.');
            return $this->sendSuccess(__('messages.membership_rules.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }


    public function export($format)
    {
        $fileName = 'membership_rules_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new MembershipRulesExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $membershipRules = MembershipRule::all();
            $pdf = Pdf::loadView('membership_rules.exports.membership_rules_pdf', compact('membershipRules'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new MembershipRulesExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $membershipRules = MembershipRule::orderBy('created_at', 'desc')->get();
            return view('membership_rules.exports.membership_rules_print', compact('membershipRules'));
        }

        abort(404);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        try {
            Excel::import(new MembershipRulesImport, $request->file('file'));
            return redirect()->back()->with('success', 'Membership rules imported successfully.');
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
            'Content-Disposition' => 'attachment; filename="membership_rules_sample.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'name',
                'customer_group',
                'customer',
                'card',
                'point_from',
                'point_to',
                'description'
            ]);

            // Add sample data
            fputcsv($file, [
                'Gold Member Rules',
                'gold',
                'existing',
                'platinum',
                '1000',
                '5000',
                'Rules for gold members with platinum card'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
