<?php

namespace App\Http\Controllers;

use App\Exports\RealEstateAgentExport;
use App\Http\Requests\RealEstateAgentRequest;
use App\Http\Requests\UpdateRealEstateAgentRequest;
use App\Models\RealEstateAgent;
use App\Queries\RealEstateAgentDataTable;
use App\Repositories\RealEstateAgentRepository;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RealEstateAgentController extends AppBaseController
{
    private $realEstateAgentRepository;

    public function __construct(RealEstateAgentRepository $repo)
    {
        $this->realEstateAgentRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new RealEstateAgentDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }

        return view('real_estate_agents.index');
    }

    public function create()
    {
        return view('real_estate_agents.create');
    }

    public function store(RealEstateAgentRequest $request)
    {
        $input = $request->all();

        // Handle file uploads
        if ($request->hasFile('profile_image')) {
            $input['profile_image'] = $request->file('profile_image')->store('real_estate_agents', 'public');
        }

        if ($request->hasFile('attachment')) {
            $input['attachment'] = $request->file('attachment')->store('real_estate_agents/attachments', 'public');
        }

        $agent = $this->realEstateAgentRepository->create($input);

        return $this->sendResponse($agent, 'Real Estate Agent saved successfully.');
    }

    public function show(RealEstateAgent $realEstateAgent)
    {
        return view('real_estate_agents.view', compact('realEstateAgent'));
    }

    public function edit(RealEstateAgent $realEstateAgent)
    {
        return view('real_estate_agents.edit', compact('realEstateAgent'));
    }

    public function update(RealEstateAgent $realEstateAgent, UpdateRealEstateAgentRequest $request)
    {
        $input = $request->all();

        $this->realEstateAgentRepository->update($input, $realEstateAgent->id);

        return $this->sendSuccess('Real Estate Agent updated successfully.');
    }

    public function destroy(RealEstateAgent $realEstateAgent)
    {
        // Delete files if they exist
        if ($realEstateAgent->profile_image) {
            Storage::disk('public')->delete($realEstateAgent->profile_image);
        }

        if ($realEstateAgent->attachment) {
            Storage::disk('public')->delete($realEstateAgent->attachment);
        }

        $realEstateAgent->delete();
        return $this->sendSuccess('Real Estate Agent deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'real_estate_agents_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new RealEstateAgentExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $agents = RealEstateAgent::all();
            $pdf = Pdf::loadView('real_estate_agents.exports.agents_pdf', compact('agents'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new RealEstateAgentExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $agents = RealEstateAgent::orderBy('created_at', 'desc')->get();
            return view('real_estate_agents.exports.agents_print', compact('agents'));
        }

        abort(404);
    }

    // public function updateStatus(Request $request, RealEstateAgent $realEstateAgent)
    // {
    //     $realEstateAgent->update(['is_active' => $request->status]);
    //     return $this->sendSuccess('Status updated successfully.');
    // }

    public function updateStatus(Request $request, RealEstateAgent $realEstateAgent)
    {
        $realEstateAgent->is_active = $request->is_active;
        $realEstateAgent->save();

        return response()->json([
            'success' => true,
            'message' => 'Real Estate Agent status updated successfully.',
        ]);
    }

    
    public function downloadAttachment(RealEstateAgent $realEstateAgent)
    {
        $filePath = $realEstateAgent->attachment;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        return Storage::disk('public')->download($filePath);
    }
}
