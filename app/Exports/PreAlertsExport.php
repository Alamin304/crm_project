<?php

// app/Exports/PreAlertsExport.php

namespace App\Exports;

use App\Models\PreAlert;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PreAlertsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PreAlert::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tracking',
            'Date',
            'Customer',
            'Shipping Company',
            'Supplier',
            'Package Description',
            'Delivery Date',
            'Purchase Price',
            'Status',
            'Created At',
            'Updated At'
        ];
    }
}
