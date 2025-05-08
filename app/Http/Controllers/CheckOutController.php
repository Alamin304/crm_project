<?php

namespace App\Http\Controllers;

use App\Repositories\CheckOutRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CheckOutController extends AppBaseController
{
    private $checkOutRepository;

    public function __construct(CheckOutRepository $checkOutRepository)
    {
        $this->checkOutRepository = $checkOutRepository;
    }
    public function index(Request $request)
    {
        // if ($request->ajax()) {
        //     return DataTables::of((new CheckInDataTable())->get())->make(true);
        // }
        return view('check_outs.index');
    }
}
