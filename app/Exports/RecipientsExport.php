<?php

namespace App\Exports;

use App\Models\Recipient;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RecipientsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Recipient::query();
    }

    public function headings(): array
    {
        return [
            'Customer',
            'Recipient',
            'Email',
            'Phone',
            'Created At'
        ];
    }

    public function map($recipient): array
    {
        return [
            $recipient->customer,
            $recipient->recipient,
            $recipient->email,
            $recipient->phone,
            $recipient->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
