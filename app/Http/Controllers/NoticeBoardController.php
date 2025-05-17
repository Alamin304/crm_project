<?php

namespace App\Http\Controllers;

use App\Exports\NoticeBoardExport;
use App\Http\Requests\NoticeBoardRequest;
use App\Http\Requests\UpdateNoticeBoardRequest;
use App\Models\NoticeBoard;
use App\Queries\NoticeBoardDataTable;
use App\Repositories\NoticeBoardRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class NoticeBoardController extends AppBaseController
{
    private $noticeBoardRepository;

    public function __construct(NoticeBoardRepository $repo)
    {
        $this->noticeBoardRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new NoticeBoardDataTable())->get())
                ->editColumn('description', function ($row) {
                    return strip_tags($row->description);
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('notice_boards.index');
    }

    public function create()
    {
        return view('notice_boards.create');
    }

    public function store(NoticeBoardRequest $request)
    {
        $input = $request->all();

        // Handle file upload
        if ($request->hasFile('notice_attachment')) {
            $file = $request->file('notice_attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('notice_attachments', $fileName, 'public');
            $input['notice_attachment'] = $filePath;
        }

        $noticeBoard = $this->noticeBoardRepository->create($input);

        return $this->sendResponse($noticeBoard, 'Notice Board saved successfully.');
    }
    public function view(NoticeBoard $noticeBoard)
    {
        // Get full public URL for the attachment
        if ($noticeBoard->notice_attachment) {
            $noticeBoard->attachment_url = Storage::url($noticeBoard->notice_attachment);
        }

        return view('notice_boards.view', compact('noticeBoard'));
    }

    public function edit(NoticeBoard $noticeBoard)
    {
        return view('notice_boards.edit', compact('noticeBoard'));
    }
    public function update(NoticeBoard $noticeBoard, UpdateNoticeBoardRequest $request)
    {
        $input = $request->all();

        // Handle file upload
        if ($request->hasFile('notice_attachment')) {
            // Delete old file if exists
            if ($noticeBoard->notice_attachment) {
                Storage::disk('public')->delete($noticeBoard->notice_attachment);
            }

            $file = $request->file('notice_attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('notice_attachments', $fileName, 'public');
            $input['notice_attachment'] = $filePath;
        }

        $this->noticeBoardRepository->update($input, $noticeBoard->id);

        return $this->sendSuccess('Notice Board updated successfully.');
    }

    public function destroy(NoticeBoard $noticeBoard)
    {
        if ($noticeBoard->notice_attachment) {
            Storage::disk('public')->delete($noticeBoard->notice_attachment);
        }

        $noticeBoard->delete();

        return $this->sendSuccess('Notice Board deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'notice_boards_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new NoticeBoardExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $noticeBoards = NoticeBoard::all();
            $pdf = Pdf::loadView('notice_boards.exports.notice_boards_pdf', compact('noticeBoards'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new NoticeBoardExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $noticeBoards = NoticeBoard::orderBy('id')->get();
            return view('notice_boards.exports.notice_boards_print', compact('noticeBoards'));
        }

        abort(404);
    }
}
