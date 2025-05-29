<?php

namespace App\Exports;

use App\Models\RentalRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RentalRequestExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return RentalRequest::with('customer')->get();
    }

    public function headings(): array
    {
        return [
            'Request Number',
            'Property Name',
            'Customer',
            'Term',
            'Start Date',
            'End Date',
            'Status',
            'Date Created'
        ];
    }

    public function map($rentalRequest): array
    {
        return [
            $rentalRequest->request_number,
            $rentalRequest->property_name,
            $rentalRequest->customer,
            $rentalRequest->term,
            $rentalRequest->contract_amount,
            $rentalRequest->property_price,
            $rentalRequest->start_date->format('Y-m-d'),
            $rentalRequest->end_date->format('Y-m-d'),
            ucfirst($rentalRequest->status),
            $rentalRequest->created_at->format('Y-m-d H:i:s')
        ];
    }
}
