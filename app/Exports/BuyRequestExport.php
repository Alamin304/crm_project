<?php

namespace App\Exports;

use App\Models\BuyRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BuyRequestExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return BuyRequest::with('customer')->get();
    }

    public function headings(): array
    {
        return [
            'Request Number',
            'Property Name',
            'Customer',
            'Property Price',
            'Contract Amount',
            'Term (months)',
            'Start Date',
            'End Date',
            'Status',
            'Date Created'
        ];
    }

    public function map($buyRequest): array
    {
        return [
            $buyRequest->request_number,
            $buyRequest->property_name,
            $buyRequest->customer,
            $buyRequest->property_price,
            $buyRequest->contract_amount,
            $buyRequest->term,
            $buyRequest->start_date->format('Y-m-d'),
            $buyRequest->end_date->format('Y-m-d'),
            ucfirst($buyRequest->status),
            $buyRequest->date_created->format('Y-m-d H:i:s')
        ];
    }
}
