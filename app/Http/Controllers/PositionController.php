<?php

namespace App\Http\Controllers;

use App\Exports\PositionsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\PositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Models\Position;
use App\Queries\PositionDataTable;
use App\Repositories\PositionRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends AppBaseController
{
    private $positionRepository;

    public function __construct(PositionRepository $positionRepo)
    {
        $this->positionRepository = $positionRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new PositionDataTable())->get())->make(true);
        }

        return view('positions.index');
    }

    public function create()
    {
        return view('positions.create');
    }

    public function store(PositionRequest $request)
    {
        $input = $request->all();
        try {
            $position = $this->positionRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($position)
                ->useLog('Position created.')
                ->log($position->name . ' Position Created');
            return $this->sendResponse($position, __('messages.positions.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(Position $position)
    {
        return view('positions.view', compact('position'));
    }

    public function edit(Position $position)
    {
        return view('positions.edit', compact('position'));
    }

    public function update(Position $position, UpdatePositionRequest $request)
    {
        $input = $request->all();
        $position = $this->positionRepository->update($input, $request->id);
        activity()->performedOn($position)->causedBy(getLoggedInUser())
            ->useLog('Position Updated')->log($position->name . ' Position updated.');
        return $this->sendSuccess(__('messages.positions.saved'));
    }

    public function destroy(Position $position)
    {
        try {
            $position->delete();
            activity()->performedOn($position)->causedBy(getLoggedInUser())
                ->useLog('Position deleted.')->log($position->name . ' Position deleted.');
            return $this->sendSuccess(__('messages.positions.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed to delete! Already in use.');
        }
    }

    public function export($format)
    {
        $fileName = 'positions_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new PositionsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $positions = Position::all();
            $pdf = Pdf::loadView('positions.exports.positions_pdf', compact('positions'));
            return $pdf->download($fileName);
        }

        abort(404);
    }
}
