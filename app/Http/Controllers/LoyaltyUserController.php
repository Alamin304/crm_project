<?php

namespace App\Http\Controllers;

use App\Exports\LoyaltyUsersExport;
use App\Models\LoyaltyUser;
use App\Queries\loyaltyUserDataTable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LoyaltyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new loyaltyUserDataTable())->get())->make(true);
        }
        return view('loyalty_users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoyaltyUser  $loyaltyUser
     * @return \Illuminate\Http\Response
     */
    public function show(LoyaltyUser $loyaltyUser)
    {
        return view('loyalty_users.view', compact('loyaltyUser'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoyaltyUser  $loyaltyUser
     * @return \Illuminate\Http\Response
     */
    public function edit(LoyaltyUser $loyaltyUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoyaltyUser  $loyaltyUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoyaltyUser $loyaltyUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoyaltyUser  $loyaltyUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoyaltyUser $loyaltyUser)
    {
        //
    }

    public function export($format)
    {
        $fileName = 'loyalty_users_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new LoyaltyUsersExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $loyaltyUsers = LoyaltyUser::orderBy('created_at', 'desc')->get();
            $pdf = Pdf::loadView('loyalty_users.exports.loyalty_users_pdf', compact('loyaltyUsers'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new LoyaltyUsersExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $loyaltyUsers = LoyaltyUser::orderBy('created_at', 'desc')->get();
            return view('loyalty_users.exports.loyalty_users_print', compact('loyaltyUsers'));
        }

        abort(404);
    }
}
