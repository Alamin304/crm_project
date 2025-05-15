<?php

namespace App\Exports;

use App\Models\CheckIn;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CheckInExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return CheckIn::orderBy('created_at', 'desc')->get();
    }

    public function map($booking): array
    {
        static $index = 0;
        $index++;
        return [
            $index,
            $booking->booking_number,
            $booking->room_type,
            $booking->room_no,
            $booking->check_in ? Date::dateTimeToExcel(Carbon::parse($booking->check_in)) : null,
            $booking->check_out ? Date::dateTimeToExcel(Carbon::parse($booking->check_out)) : null,
            $booking->booking_status ? 'Confirmed' : 'Pending',
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.check_in.id'),
            __('messages.check_in.booking_number'),
            __('messages.check_in.room_type'),
            __('messages.check_in.room_no'),
            __('messages.check_in.check_in'),
            __('messages.check_in.check_out'),
            __('messages.check_in.booking_status'),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_DATETIME,
            'F' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
