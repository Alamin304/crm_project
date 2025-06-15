<?php

namespace App\Exports;

use App\Models\ManufacturingOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ManufacturingOrderExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ManufacturingOrder::all();
    }

    public function headings(): array
    {
        return [
            'Product',
            'Deadline',
            'Quantity',
            'Plan From',
            'Unit of Measure',
            'Responsible',
            'BOM Code',
            'Reference Code',
            'Routing',
            'Created At'
        ];
    }

    public function map($order): array
    {
        return [
            $order->product,
            $order->deadline,
            $order->quantity,
            $order->plan_from,
            $order->unit_of_measure,
            $order->responsible,
            $order->bom_code,
            $order->reference_code,
            $order->routing,
            $order->created_at,
        ];
    }
}
