<?php

namespace App\Exports;

use App\Models\WorkOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WorkOrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return WorkOrder::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Work Order',
            'Start Date',
            'Work Center',
            'Manufacturing Order',
            'Product Quantity',
            'Unit',
            'Status',
            'Created At',
            'Updated At'
        ];
    }
}
