<?php

namespace App\Exports;

use App\Models\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RequestsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Request::all();
    }

    public function headings(): array
    {
        return [
            'Title',
            'Assets',
            'Checkout For',
            'Note',
            'Status',
            'Created At',
            'Updated At'
        ];
    }
}
