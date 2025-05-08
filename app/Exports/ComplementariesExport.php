<?php

namespace App\Exports;

use App\Models\Complementary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ComplementariesExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Complementary::query()
            ->orderBy('id')
            ->get();
    }

    public function map($complementary): array
    {
        return [
            $complementary->id,
            $complementary->room_type,
            $complementary->complementary,
            $complementary->rate,
        ];
    }

    public function headings(): array
    {
        return [
            __('SL'),
            __('Room Type'),
            __('Complementary'),
            __('Rate'),
        ];
    }
}
