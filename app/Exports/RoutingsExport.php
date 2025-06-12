<?php

namespace App\Exports;

use App\Models\Routing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RoutingsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Routing::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Routing Code',
            'Routing Name',
            'Note',
            'Created At',
            'Updated At'
        ];
    }
}
