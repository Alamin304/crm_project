<?php

namespace App\Http\Controllers;

use App\Models\BookingList;
use App\Exports\BookingListsExport;
use Illuminate\Http\Request;
use App\Queries\BookingListDataTable;
use App\Http\Requests\BookingListRequest;
use App\Http\Requests\UpdateBookingListRequest;
use App\Repositories\BookingListRepository;
use Throwable;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class BookingListController extends AppBaseController
{
    private $bookingListRepository;

    public function __construct(BookingListRepository $bookingListRepository)
    {
        $this->bookingListRepository = $bookingListRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new BookingListDataTable())->get())->make(true);
        }
        return view('booking_lists.index');
    }

    public function create()
    {
        return view('booking_lists.create');
    }

    public function store(BookingListRequest $request)
    {
        try {
            $booking = $this->bookingListRepository->create($request->all());
            activity()->causedBy(getLoggedInUser())
                ->performedOn($booking)
                ->log("Booking Created: {$booking->booking_number}");
            return $this->sendResponse($booking, 'Booking created successfully.');
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(BookingList $bookingList)
    {
        return view('booking_lists.view', compact('bookingList'));
    }

    public function edit(BookingList $bookingList)
    {
        return view('booking_lists.edit', compact('bookingList'));
    }

    public function update(UpdateBookingListRequest $request, BookingList $bookingList)
    {
        $updated = $this->bookingListRepository->update($request->all(), $bookingList->id);
        activity()->causedBy(getLoggedInUser())
            ->performedOn($bookingList)
            ->log("Booking Updated: {$bookingList->booking_number}");
        return $this->sendSuccess('Booking updated successfully.');
    }

    public function destroy(BookingList $bookingList)
    {
        $bookingList->delete();
        activity()->causedBy(getLoggedInUser())
            ->performedOn($bookingList)
            ->log("Booking Deleted: {$bookingList->booking_number}");
        return $this->sendSuccess('Booking deleted successfully.');
    }

    // public function export($format)
    // {
    //     $fileName = 'booking_lists_export_' . now()->format('Y-m-d') . '.' . $format;

    //     if ($format === 'csv') {
    //         return Excel::download(new BookingListsExport, $fileName, \Maatwebsite\Excel\Excel::CSV);
    //     }

    //     if ($format === 'pdf') {
    //         $bookingLists = BookingList::all();
    //         $pdf = Pdf::loadView('booking_lists.exports.booking_lists_pdf', compact('bookingLists'));
    //         return $pdf->download($fileName);
    //     }

    //     abort(404);
    // }
}

