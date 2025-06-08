<?php
namespace App\Http\Controllers;

use App\Http\Requests\AuditRequest;
use App\Repositories\AuditRepository;
use App\Models\Audit;
use App\Queries\AuditDataTable;
use Exception;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class AuditController extends AppBaseController
{
    private $auditRepository;

    public function __construct(AuditRepository $auditRepo)
    {
        $this->auditRepository = $auditRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new AuditDataTable())->get())
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<div style="float: right;">
                        <a href="#" title="Delete" class="btn btn-danger action-btn has-icon delete-btn" data-id="'.$row->id.'" style="margin:2px;">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('audits.index');
    }

    public function create()
    {
        $auditors = ['John Doe', 'Jane Smith', 'Robert Johnson', 'Emily Davis', 'Michael Brown'];
        $statuses = ['new' => 'New', 'approved' => 'Approved', 'rejected' => 'Rejected'];

        return view('audits.create', compact('auditors', 'statuses'));
    }

    public function store(AuditRequest $request)
    {
        try {
            $input = $request->all();
            $audit = $this->auditRepository->create($input);

            activity()->causedBy(getLoggedInUser())
                ->performedOn($audit)
                ->useLog('Audit created.')
                ->log($audit->title . ' Audit Created');

            return $this->sendResponse($audit, __('messages.audit.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function destroy(Audit $audit)
    {
        try {
            $audit->delete();

            activity()->performedOn($audit)->causedBy(getLoggedInUser())
                ->useLog('Audit deleted.')->log($audit->title . ' Audit deleted.');

            return $this->sendSuccess(__('messages.audit.delete'));
        } catch (Exception $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }
}
