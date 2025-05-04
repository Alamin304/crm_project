<?php

namespace App\Http\Controllers;

use App\Http\Requests\BedRequest;
use App\Http\Requests\UpdateBedRequest;
use App\Models\Bed;
use App\Queries\BedDataTable;
use App\Repositories\BedRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class BedController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     private $bedRepository;

    public function __construct(BedRepository $bedRepo)
    {
        $this->bedRepository = $bedRepo;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new BedDataTable())->get())->make(true);
        }
        return view('beds.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('beds.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BedRequest $request)
    {
        $input = $request->all();
        try {
            $bed = $this->bedRepository->create($input);
            activity()->causedBy(getLoggedInUser())
                ->performedOn($bed)
                ->useLog('Bed created.')
                ->log($bed->name . ' Bed Created');
            return $this->sendResponse($bed, __('messages.beds.saved'));
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bed  $bed
     * @return \Illuminate\Http\Response
     */
    public function show(Bed $bed)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bed  $bed
     * @return \Illuminate\Http\Response
     */
    public function edit(Bed $bed)
    {
        return view('beds.edit', compact('bed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bed  $bed
     * @return \Illuminate\Http\Response
     */
    public function update(Bed $bed, UpdateBedRequest $updateBedRequest)
    {
        $input = $updateBedRequest->all();
        $bed = $this->bedRepository->update($input, $updateBedRequest->id);
        activity()->performedOn($bed)->causedBy(getLoggedInUser())
            ->useLog('Bed Updated')->log($bed->name . ' Bed updated.');
        return $this->sendSuccess(__('messages.beds.saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bed  $bed
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bed $bed)
    {
        try {
            $bed->delete();
            activity()->performedOn($bed)->causedBy(getLoggedInUser())
                ->useLog('Bed deleted.')->log($bed->name . ' Bed deleted.');
            return $this->sendSuccess(__('messages.beds.delete'));
        } catch (QueryException $e) {
            return $this->sendError('Failed To delete!! Already in use.');
        }
    }
}
