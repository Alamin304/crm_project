<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReservationsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Reservation::select([
            'customer_name',
            'table_no',
            'number_of_people',
            'start_time',
            'end_time',
            'date',
            'status',
        ])->get();
    }

    public function headings(): array
    {
        return [
            __('messages.reservations.customer_name'),
            __('messages.reservations.table_no'),
            __('messages.reservations.number_of_people'),
            __('messages.reservations.start_time'),
            __('messages.reservations.end_time'),
            __('messages.reservations.date'),
            __('messages.reservations.status'),
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->customer_name,
            $reservation->table_no,
            $reservation->number_of_people,
            $reservation->start_time,
            $reservation->end_time,
            $reservation->date,
            ucfirst($reservation->status),
        ];
    }
}

