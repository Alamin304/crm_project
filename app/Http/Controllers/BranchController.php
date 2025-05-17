<?php

namespace App\Http\Controllers;

use App\Exports\BranchesExport;
use App\Queries\BranchDataTable;
use Illuminate\Http\Request;
use App\Repositories\BranchRepository;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Exception;
use App\Http\Requests\BranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use Illuminate\Database\QueryException;
use App\Models\Supplier;
use App\Models\Branch;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class BranchController extends AppBaseController
{
    /**
     * @var BranchRepository
     */
    private $branchRepository;
    public function __construct(BranchRepository $branchRepo)
    {
        $this->branchRepository = $branchRepo;
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
            return DataTables::of((new BranchDataTable())->get($request->only(['group'])))->make(true);
        }
        return view('branches.index');
    }

    public function create()
    {

        $countries = $this->branchRepository->getCountries();
        $currencies = $this->branchRepository->getCurrencies();
        $company = $this->branchRepository->getCompanyName();
        $banks = $this->branchRepository->getBanks();


        return view('branches.create', compact(['countries', 'currencies', 'company', 'banks']));
    }

    public function store(BranchRequest $request)
    {


        $input = $request->all();

        try {
            $designation = $this->branchRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($designation)
                ->useLog('Branch created.')
                ->log(' Branch.');
            Flash::success(__('messages.branches.saved'));
            return $this->sendResponse($designation, __('messages.branches.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }
    public function destroy(Branch $branch)
    {

        try {
            // Check if the branch has any associated users
            if ($branch->UsersBranches()->exists()) {
                // Prevent deletion if users are associated
                return $this->sendError('Failed To delete!! Branch is already in use by users.');
            }

            // Proceed with deletion if no users are associated
            $deleted = $branch->delete();

            // Log the deletion activity
            activity()->performedOn($branch)
                ->causedBy(getLoggedInUser())
                ->useLog('Branch deleted.')
                ->log('Branch deleted.');

            return $this->sendSuccess(__('messages.branches.delete'));
        } catch (QueryException $e) {
            // Catch any database exceptions (e.g., foreign key constraints)
            return $this->sendError('Failed to delete!! Error occurred during deletion.');
        }
    }


    public function edit(Branch $branch)
    {
        $countries = $this->branchRepository->getCountries();
        $currencies = $this->branchRepository->getCurrencies();
        $company = $this->branchRepository->getCompanyName();
        $branch->load(['country', 'currency']);
        $banks = $this->branchRepository->getBanks();

        return view('branches.edit', compact(['branch', 'countries', 'currencies', 'company', 'banks']));
    }
    public function view(Branch $branch)
    {
        $branch->load('bank');
        return view('branches.view', compact(['branch']));
    }
    public function update(Branch $branch, UpdateBranchRequest $updateBranchRequest)
    {
        $input = $updateBranchRequest->all();

        $subDepartment = $this->branchRepository->update_branch($updateBranchRequest->id, $input);
        activity()->performedOn($subDepartment)->causedBy(getLoggedInUser())
            ->useLog('Branch Updated')->log('Branch updated.');
        Flash::success(__('messages.branches.saved'));
        return $this->sendSuccess(__('messages.branches.saved'));
    }

    public function export($format)
    {
        $fileName = 'branches_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new BranchesExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $branches = Branch::with(['country', 'bank'])->orderBy('name')->get();
            $pdf = Pdf::loadView('branches.exports.branches_pdf', compact('branches'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new BranchesExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $branches = Branch::with(['country', 'bank'])->orderBy('name')->get();
            return view('branches.exports.branches_print', compact('branches'));
        }

        abort(404);
    }
}
