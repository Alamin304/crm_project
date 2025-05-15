<?php

namespace App\Exports;

use App\Models\BookingSource;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class BookingSourceExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return BookingSource::orderBy('id')->get();
    }

    public function map($source): array
    {
         static $index = 0;
        $index++;
        return [
            $index,
            $source->booking_type,
            $source->booking_source,
            $source->commission_rate . '%',
        ];
    }

    public function headings(): array
    {
        return [
            'SL',
            'Booking Type',
            'Booking Source',
            'Commission Rate (%)',
        ];
    }
}

