<?php

namespace App\Http\Controllers;

use App\Exports\TrainingProgramExport;
use App\Http\Requests\TrainingProgramRequest;
use App\Http\Requests\UpdateTrainingProgramRequest;
use App\Imports\TrainingProgramImport;
use App\Models\TrainingProgram;
use App\Queries\TrainingProgramDataTable;
use App\Repositories\TrainingProgramRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TrainingProgramController extends AppBaseController
{
    private $trainingProgramRepository;

    public function __construct(TrainingProgramRepository $repo)
    {
        $this->trainingProgramRepository = $repo;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(TrainingProgram::select([
                'id',
                'program_name',
                'training_type',
                'description',
                'point',
                'created_at'
            ]))
                ->editColumn('description', fn($row) => strip_tags($row->description))
                ->editColumn('created_at', fn($row) => optional($row->created_at)->format('Y-m-d'))
                ->addIndexColumn()
                ->make(true);
        }

        return view('training_programs.index');
    }



    public function create()
    {
        return view('training_programs.create');
    }

    public function store(TrainingProgramRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('attachment')) {
            $input['attachment'] = $request->file('attachment');
        }

        $trainingProgram = $this->trainingProgramRepository->create($input);

        return $this->sendResponse($trainingProgram, 'Training Program saved successfully.');
    }

    public function show(TrainingProgram $trainingProgram)
    {
        return view('training_programs.view', compact('trainingProgram'));
    }

    public function edit(TrainingProgram $trainingProgram)
    {
        return view('training_programs.edit', compact('trainingProgram'));
    }

    public function update(TrainingProgram $trainingProgram, UpdateTrainingProgramRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('attachment')) {
            $input['attachment'] = $request->file('attachment');
        }

        $this->trainingProgramRepository->update($input, $trainingProgram->id);

        return $this->sendSuccess('Training Program updated successfully.');
    }

    public function destroy(TrainingProgram $trainingProgram)
    {
        $this->trainingProgramRepository->delete($trainingProgram->id);

        return $this->sendSuccess('Training Program deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'training_programs_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new TrainingProgramExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $trainingPrograms = TrainingProgram::all();
            $pdf = Pdf::loadView('training_programs.exports.training_programs_pdf', compact('trainingPrograms'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new TrainingProgramExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $trainingPrograms = TrainingProgram::orderBy('id')->get();
            return view('training_programs.exports.training_programs_print', compact('trainingPrograms'));
        }

        abort(404);
    }

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:xlsx,xls,csv'
    //     ]);

    //     Excel::import(new TrainingProgramImport, $request->file('file'));

    //     return $this->sendSuccess('Training Programs imported successfully.');
    // }
}
