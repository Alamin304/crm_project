<?php

namespace App\Exports;

use App\Models\Consumable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConsumablesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Consumable::all()->map(function ($consumable) {
            return [
                $consumable->consumable_name,
                $consumable->category_name,
                $consumable->supplier,
                $consumable->manufacturer,
                $consumable->location,
                $consumable->model_number,
                $consumable->purchase_cost,
                $consumable->purchase_date,
                $consumable->quantity,
                $consumable->min_quantity,

            ];
        });
    }

    public function headings(): array
    {
        return [
            'Consumable Name',
            'Category',
            'Supplier',
            'Manufacturer',
            'Location',
            'Model Number',
            'Purchase Cost',
            'Purchase Date',
            'Quantity',
            'Min Quantity',

        ];
    }
}
