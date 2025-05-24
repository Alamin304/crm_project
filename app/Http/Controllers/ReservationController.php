<?php

// app/Http/Controllers/ReservationController.php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Exports\ReservationsExport;
use Illuminate\Http\Request;
use App\Queries\ReservationDataTable;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Imports\ReservationImport;
use App\Repositories\ReservationRepository;
use Throwable;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class ReservationController extends AppBaseController
{
    private $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new ReservationDataTable())->get())
                ->addIndexColumn()
                ->make(true);
        }
        return view('reservations.index');
    }

    public function create()
    {
        return view('reservations.create');
    }

    public function store(ReservationRequest $request)
    {
        try {
            $reservation = $this->reservationRepository->create($request->all());
            activity()->causedBy(getLoggedInUser())
                ->performedOn($reservation)
                ->log("Reservation Created: {$reservation->customer_name}");
            return $this->sendResponse($reservation, 'Reservation created successfully.');
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function view(Reservation $reservation)
    {
        return view('reservations.view', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $updated = $this->reservationRepository->update($request->all(), $reservation->id);
        activity()->causedBy(getLoggedInUser())
            ->performedOn($reservation)
            ->log("Reservation Updated: {$reservation->customer_name}");
        return $this->sendSuccess('Reservation updated successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        activity()->causedBy(getLoggedInUser())
            ->performedOn($reservation)
            ->log("Reservation Deleted: {$reservation->customer_name}");
        return $this->sendSuccess('Reservation deleted successfully.');
    }

    public function export($format)
    {
        $fileName = 'reservations_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new ReservationsExport, $fileName, \Maatwebsite\Excel\Excel::CSV);
        }

        if ($format === 'pdf') {
            $reservations = Reservation::all();
            $pdf = Pdf::loadView('reservations.exports.reservations_pdf', compact('reservations'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new ReservationsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $reservations = Reservation::orderBy('created_at', 'desc')->get();
            return view('reservations.exports.reservations_print', compact('reservations'));
        }

        abort(404);
    }

    // public function downloadSampleCsv(): StreamedResponse
    // {
    //     $headers = [
    //         "Content-type"        => "text/csv",
    //         "Content-Disposition" => "attachment; filename=reservations_sample.csv",
    //         "Pragma"              => "no-cache",
    //         "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
    //         "Expires"             => "0"
    //     ];

    //     $columns = [
    //         'customer_name',
    //         'table_no',
    //         'number_of_people',
    //         'start_time',
    //         'end_time',
    //         'date',
    //         'status'
    //     ];

    //     $rows = [
    //         [
    //             'John Doe',
    //             'T1',
    //             '4',
    //             '19:00',
    //             '21:00',
    //             '2023-06-15',
    //             'confirmed'
    //         ],
    //         [
    //             'Jane Smith',
    //             'T5',
    //             '2',
    //             '20:00',
    //             '22:00',
    //             '2023-06-18',
    //             'pending'
    //         ],
    //     ];

    //     $callback = function () use ($columns, $rows) {
    //         $file = fopen('php://output', 'w');
    //         fputcsv($file, $columns);

    //         foreach ($rows as $row) {
    //             fputcsv($file, $row);
    //         }

    //         fclose($file);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:csv,txt|max:2048',
    //     ]);

    //     try {
    //         $path = $request->file('file')->getRealPath();
    //         $file = fopen($path, 'r');
    //         $headers = fgetcsv($file);

    //         $expectedHeaders = [
    //             'customer_name',
    //             'table_no',
    //             'number_of_people',
    //             'start_time',
    //             'end_time',
    //             'date',
    //             'status'
    //         ];

    //         if (array_diff($expectedHeaders, array_map('strtolower', $headers))) {
    //             fclose($file);
    //             return redirect()->back()->with('error', 'Invalid file format. Please download the sample CSV for the correct format.');
    //         }

    //         fclose($file);

    //         Excel::import($import = new ReservationImport, $request->file('file'));

    //         if (!empty($import->failures())) {
    //             return redirect()->back()->with([
    //                 'failures' => $import->failures(),
    //             ]);
    //         }

    //         return redirect()->route('reservations.index')->with('success', 'Reservations imported successfully.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'There was a problem importing the file. ' . $e->getMessage());
    //     }
    // }
}
