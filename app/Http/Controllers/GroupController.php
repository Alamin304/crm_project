<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Http\Requests\GroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Repositories\GroupRepository;
use App\Queries\GroupDataTable;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GroupsExport;
use App\Imports\GroupImport;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GroupController extends AppBaseController
{
    private GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new GroupDataTable())->get())->make(true);
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
            $group = $this->groupRepository->create($input);

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
        $this->groupRepository->update($input, $group->id);

        activity()->performedOn($group)->causedBy(getLoggedInUser())
            ->useLog('Group Updated')->log($group->group_name . ' Group updated.');

        return $this->sendSuccess(__('messages.groups.saved'));
    }

    public function destroy(Group $group)
    {
        try {
            $this->groupRepository->delete($group->id);

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
            $groups = (new GroupDataTable())->get()->get();
            $pdf = PDF::loadView('groups.exports.groups_pdf', compact('groups'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new GroupsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $groups = Group::orderBy('group_name', 'asc')->get();
            return view('groups.exports.groups_print', compact('groups'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=sample_groups.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = ['group_name', 'description'];
        $rows = [
            ['Sample Group', 'This is a sample group description.'],
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

        // Prevent duplicate import if groups already exist
        if (\App\Models\Group::exists()) {
            return redirect()->back()->with('error', 'Import failed: Groups already exist in the database.');
        }

        try {
            // Validate CSV headers manually
            if ($request->file('file')->getClientOriginalExtension() === 'csv' || $request->file('file')->getClientOriginalExtension() === 'txt') {
                $path = $request->file('file')->getRealPath();
                $file = fopen($path, 'r');
                $headers = fgetcsv($file);
                fclose($file);

                $expectedHeaders = ['group_name', 'description'];
                if (array_map('strtolower', $headers) !== $expectedHeaders) {
                    return redirect()->back()->with('error', 'Invalid file format. Required headers: group_name, description.');
                }
            }

            // Import with Laravel Excel
            Excel::import($import = new GroupImport, $request->file('file'));

            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'error' => 'Some rows failed validation.',
                    'failures' => $import->failures(),
                ]);
            }

            return redirect()->route('groups.index')->with('success', 'Groups imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
