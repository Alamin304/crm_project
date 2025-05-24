<?php

namespace App\Http\Controllers;

use App\Exports\CampaignsExport;
use App\Http\Requests\CampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Imports\CampaignsImport;
use App\Models\Campaign;
use App\Queries\CampaignDataTable;
use App\Repositories\CampaignRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class CampaignController extends AppBaseController
{
    private $campaignRepository;

    public function __construct(CampaignRepository $repo)
    {
        $this->campaignRepository = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dataTable = new CampaignDataTable();
            return $dataTable->dataTable($dataTable->get())->make(true);
        }

        return view('campaigns.index');
    }

    public function create()
    {
        return view('campaigns.create');
    }

    public function store(CampaignRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('attachment')) {
            $input['attachment'] = $request->file('attachment');
        }

        $campaign = $this->campaignRepository->create($input);

        return $this->sendResponse($campaign, 'Campaign saved successfully.');
    }

    public function show(Campaign $campaign)
    {
        return view('campaigns.view', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        return view('campaigns.edit', compact('campaign'));
    }

    public function update(Campaign $campaign, UpdateCampaignRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('attachment')) {
            $input['attachment'] = $request->file('attachment');
        }

        $this->campaignRepository->update($input, $campaign->id);

        return $this->sendSuccess('Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $this->campaignRepository->delete($campaign->id);

        return $this->sendSuccess('Campaign deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'campaigns_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new CampaignsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $campaigns = Campaign::all();
            $pdf = Pdf::loadView('campaigns.exports.campaigns_pdf', compact('campaigns'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new CampaignsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $campaigns = Campaign::orderBy('id')->get();
            return view('campaigns.exports.campaigns_print', compact('campaigns'));
        }

        abort(404);
    }
}
