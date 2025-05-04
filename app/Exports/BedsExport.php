<?php

namespace App\Exports;

use App\Models\Bed;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BedsExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return Bed::all();
    }

    public function map($bed): array
    {
        return [
            $bed->name,
            strip_tags($bed->description),
            $bed->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Created At',
        ];
    }
}
