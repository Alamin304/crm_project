<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Http\Requests\GroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GroupsExport;
use Throwable;
use Illuminate\Database\QueryException;

class GroupController extends AppBaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Group::query())->make(true);
        }
        return view('groups.index');
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(GroupRequest $request)
    {
        $input = $request->all();
        try {
            $group = Group::create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($group)
                ->useLog('Group created.')
                ->log($group->group_name . ' Group Created');
            return $this->sendResponse($group, __('messages.groups.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(Group $group)
    {
        return view('groups.view', compact('group'));
    }

    public function edit(Group $group)
    {
        return view('groups.edit', compact('group'));
    }

    public function update(Group $group, UpdateGroupRequest $request)
    {
        $input = $request->all();
        $group->update($input);
        activity()->performedOn($group)->causedBy(getLoggedInUser())
            ->useLog('Group Updated')->log($group->group_name . ' Group updated.');
        return $this->sendSuccess(__('messages.groups.saved'));
    }

    public function destroy(Group $group)
    {
        try {
            $group->delete();
            activity()->performedOn($group)->causedBy(getLoggedInUser())
                ->useLog('Group deleted.')->log($group->group_name . ' Group deleted.');
            return $this->sendSuccess(__('messages.groups.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'groups_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new GroupsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $groups = Group::all();
            $pdf = PDF::loadView('groups.exports.groups_pdf', compact('groups'));
            return $pdf->download($fileName);
        }

        abort(404);
    }
}

