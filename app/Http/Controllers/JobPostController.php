<?php

namespace App\Http\Controllers;

use App\Exports\JobPostsExport;
use App\Queries\JobPostDataTable;
use Illuminate\Http\Request;
use App\Repositories\JobPostRepository;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Exception;
use App\Http\Requests\JobPostRequest;
use App\Models\JobPost;
use App\Http\Requests\UpdateJobPostRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\JobCategory;
use Throwable;

class JobPostController extends AppBaseController
{
    /**
     * @var JobPostRepository
     */
    private $jobPostRepository;

    public function __construct(JobPostRepository $jobPostRepo)
    {
        $this->jobPostRepository = $jobPostRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
   public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new JobPostDataTable())->get())->make(true);
        }

        return view('job_posts.index');
    }

    public function create()
    {
        $categories = JobCategory::where('status', true)
                    ->pluck('name', 'id')
                    ->toArray();
        return view('job_posts.create', compact('categories'));
    }

    public function store(JobPostRequest $request)
    {
        $input = $request->all();

        try {
            $jobPost = $this->jobPostRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($jobPost)
                ->useLog('Job Post created.')
                ->log($jobPost->job_title . ' created');

            Flash::success(__('messages.job_posts.saved'));
            return $this->sendResponse($jobPost, __('messages.job_posts.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function destroy(JobPost $jobPost)
    {
        try {
            $jobPost->delete();
            activity()->performedOn($jobPost)->causedBy(getLoggedInUser())
                ->useLog('Job Post deleted.')->log($jobPost->job_title . ' deleted.');
            return $this->sendSuccess(__('messages.job_posts.deleted'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }

    public function view(JobPost $jobPost)
    {
        $jobPost->load('category');
        return view('job_posts.view', compact('jobPost'));
    }

    public function edit(JobPost $jobPost)
    {
       $categories = JobCategory::where('status', true)
                    ->pluck('name', 'id')
                    ->toArray();
        return view('job_posts.edit', compact(['jobPost', 'categories']));
    }

    public function update(JobPost $jobPost, UpdateJobPostRequest $request)
    {
        $input = $request->all();
        $updatedJobPost = $this->jobPostRepository->update($input, $jobPost->id);

        activity()->performedOn($updatedJobPost)->causedBy(getLoggedInUser())
            ->useLog('Job Post Updated')->log($updatedJobPost->job_title . ' updated.');

        Flash::success(__('messages.job_posts.updated'));
        return $this->sendSuccess(__('messages.job_posts.updated'));
    }

    public function export($format)
    {
        $fileName = 'job_posts_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new JobPostsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $jobPosts = JobPost::with(['category'])->get();
            $pdf = Pdf::loadView('job_posts.exports.job_posts_pdf', compact('jobPosts'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new JobPostsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        abort(404);
    }

    // public function toggleStatus(JobPost $jobPost)
    // {
    //     $jobPost->status = !$jobPost->status;
    //     $jobPost->save();

    //     $status = $jobPost->status ? 'activated' : 'deactivated';
    //     activity()->performedOn($jobPost)->causedBy(getLoggedInUser())
    //         ->useLog('Job Post Status Changed')->log($jobPost->job_title . ' ' . $status);

    //     return $this->sendSuccess(__('messages.job_posts.status_changed'));
    // }
}
