<?php

namespace App\Http\Controllers;

use App\Exports\JobCategoriesExport;
use App\Http\Requests\JobCategoryRequest;
use App\Http\Requests\UpdateJobCategoryRequest;
use App\Imports\JobCategoryImport;
use App\Models\JobCategory;
use App\Queries\JobCategoryDataTable;
use App\Repositories\JobCategoryRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use Yajra\DataTables\Facades\DataTables;


class JobCategoryController extends AppBaseController
{
    private $jobCategoryRepository;

    public function __construct(JobCategoryRepository $jobCategoryRepo)
    {
        $this->jobCategoryRepository = $jobCategoryRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new JobCategoryDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('job_categories.index');
    }

    public function create()
    {
        return view('job_categories.create');
    }

    public function store(JobCategoryRequest $request)
    {
        $input = $request->all();
        try {
            $jobCategory = $this->jobCategoryRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($jobCategory)
                ->useLog('Job Category created.')
                ->log($jobCategory->name . ' category created.');
            return $this->sendResponse($jobCategory, __('messages.job_categories.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(JobCategory $jobCategory)
    {
        return view('job_categories.view', compact('jobCategory'));
    }

    public function edit(JobCategory $jobCategory)
    {
        return view('job_categories.edit', compact('jobCategory'));
    }

    public function update(JobCategory $jobCategory, UpdateJobCategoryRequest $request)
    {
        if (!$jobCategory) {
            return redirect()->route('job-categories.index')->withErrors('Job category not found.');
        }

        $input = $request->all();

        $updated = $this->jobCategoryRepository->update($input, $jobCategory->id);

        activity()->performedOn($updated)->causedBy(getLoggedInUser())
            ->useLog('Job Category updated.')
            ->log($updated->name . ' category updated.');

        return $this->sendSuccess(__('messages.job_categories.updated'));
    }

    // public function status(JobCategory $jobCategory, Request $request)
    // {
    //     try {
    //         $jobCategory->update(['status' => $request->status]);
    //         activity()->performedOn($jobCategory)->causedBy(getLoggedInUser())
    //             ->useLog('Job Category status updated.')
    //             ->log($jobCategory->name . ' category status updated to ' . ($request->status ? 'Active' : 'Inactive'));
    //         return $this->sendSuccess(__('messages.job_categories.status_updated'));
    //     } catch (\Exception $e) {
    //         return $this->sendError($e->getMessage());
    //     }
    // }

    public function status(JobCategory $jobCategory, Request $request)
    {
        try {
            // Check if end_date is in the past and status is active (1)
            if (Carbon::parse($jobCategory->end_date)->isPast() && $jobCategory->status == 1) {
                $jobCategory->update(['status' => 0]);

                activity()->performedOn($jobCategory)->causedBy(getLoggedInUser())
                    ->useLog('Job Category auto-inactivated.')
                    ->log($jobCategory->name . ' category status auto-inactivated due to end date expiry.');

                return $this->sendSuccess(__('messages.job_categories.updated'));
            }

            // Else update with requested status
            $jobCategory->update(['status' => $request->status]);

            activity()->performedOn($jobCategory)->causedBy(getLoggedInUser())
                ->useLog('Job Category status updated.')
                ->log($jobCategory->name . ' category status updated to ' . ($request->status ? 'Active' : 'Inactive'));

            return $this->sendSuccess(__('messages.job_categories.updated'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }


    public function destroy(JobCategory $jobCategory)
    {
        try {
            $jobCategory->delete();
            activity()->performedOn($jobCategory)->causedBy(getLoggedInUser())
                ->useLog('Job Category deleted.')
                ->log($jobCategory->name . ' category deleted.');
            return $this->sendSuccess(__('messages.job_categories.deleted'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'job_categories_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new JobCategoriesExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $jobCategories = JobCategory::all();
            $pdf = PDF::loadView('job_categories.exports.job_categories_pdf', compact('jobCategories'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new JobCategoriesExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $jobCategories = JobCategory::orderBy('id')->get();
            return view('job_categories.exports.job_categories_print', compact('jobCategories'));
        }

        abort(404);
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=job_categories_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['name', 'description', 'startdate', 'enddate'];
        $rows = [
            ['Developer', 'Writes and maintains code', '2025-06-01', '2025-12-31'],
            ['Designer', 'Designs UI/UX interfaces', '2025-07-01', '2025-10-15'],
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

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:csv,txt|max:2048',
    //     ]);

    //     try {
    //         Excel::import(new JobCategoryImport, $request->file('file'));

    //         return redirect()->route('job-categories.index')->with('success', 'Job Categories imported successfully.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
    //     }
    // }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        if (JobCategory::exists()) {
            return redirect()->back()->with('error', 'Import failed: Job categories already exist in the database.');
        }

        try {
            $path = $request->file('file')->getRealPath();
            $file = fopen($path, 'r');
            $headers = fgetcsv($file);

            $expectedHeaders = ['name', 'description', 'startdate', 'enddate'];

            if (array_map('strtolower', $headers) !== $expectedHeaders) {
                return redirect()->back()->with('error', 'Invalid file format. Required headers: name, description, startdate, enddate.');
            }

            fclose($file);

            // Attempt import
            Excel::import($import = new JobCategoryImport, $request->file('file'));

            // Check for row validation failures
            if (!empty($import->failures())) {
                return redirect()->back()->with([
                    'failures' => $import->failures(),
                ]);
            }


            return redirect()->route('job-categories.index')->with('success', 'Job categories imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
        }
    }
}
