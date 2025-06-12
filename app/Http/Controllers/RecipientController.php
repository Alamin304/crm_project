<?php

namespace App\Http\Controllers;

use App\Exports\RecipientsExport;
use App\Models\Recipient;
use App\Queries\RecipientDataTable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RecipientController extends AppBaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new RecipientDataTable())->get())->make(true);
        }
        return view('recipients.index');
    }

    public function export($format)
    {
        $fileName = 'recipients_export_' . now()->format('Y-m-d') . '.' . $format;

        if ($format === 'csv') {
            return Excel::download(new RecipientsExport, $fileName, \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        }

        if ($format === 'pdf') {
            $recipients = Recipient::all();
            $pdf = Pdf::loadView('recipients.exports.recipients_pdf', compact('recipients'));
            return $pdf->download($fileName);
        }

        if ($format === 'xlsx') {
            return Excel::download(new RecipientsExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($format === 'print') {
            $recipients = Recipient::orderBy('created_at', 'desc')->get();
            return view('recipients.exports.recipients_print', compact('recipients'));
        }

        abort(404);
    }
}
