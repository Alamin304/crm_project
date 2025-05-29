<?php

namespace App\Exports;

use App\Models\BusinessBroker;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BusinessBrokerExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return BusinessBroker::all();
    }

    public function headings(): array
    {
        return [
            'Code',
            'Owner Name',
            'Email',
            'Phone Number',
            'Address',
            'City',
            'Country',
            'Status',
            'Verification Status',
            'Privacy'
        ];
    }

    public function map($broker): array
    {
        return [
            $broker->code,
            $broker->owner_name,
            $broker->email,
            $broker->phone_number,
            $broker->address,
            $broker->city,
            $broker->country,
            $broker->is_active ? 'Active' : 'Inactive',
            ucfirst($broker->verification_status),
            ucfirst($broker->privacy)
        ];
    }
}
