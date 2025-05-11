<?php

namespace App\Exports;

use App\Models\Position;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PositionsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Position::orderBy('id')->get();
    }

    public function map($position): array
    {
        return [
            $position->id,
            $position->name,
            $position->status ? 'Active' : 'Inactive',
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.positions.id'),
            __('messages.positions.name'),
            __('messages.positions.status'),
        ];
    }
}
