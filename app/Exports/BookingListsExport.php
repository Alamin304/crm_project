<?php

namespace App\Exports;

use App\Models\BookingList;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BookingListsExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    public function collection()
    {
        return BookingList::orderBy('created_at', 'desc')->get();
    }

    public function map($booking): array
    {
        return [
            $booking->id,
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
            __('messages.booking_lists.id'),
            __('messages.booking_lists.booking_number'),
            __('messages.booking_lists.room_type'),
            __('messages.booking_lists.room_no'),
            __('messages.booking_lists.check_in'),
            __('messages.booking_lists.check_out'),
            __('messages.booking_lists.booking_status'),
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
