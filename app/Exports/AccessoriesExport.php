<?php

namespace App\Exports;

use App\Models\Accessory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AccessoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Accessory::all([
            'accessory_name',
            'category_name',
            'supplier',
            'manufacturer',
            'location',
            'model_number',
            'purchase_cost',
            'purchase_date',
            'quantity',
            'min_quantity',
        ]);
    }

    public function headings(): array
    {
        return [
            'Accessory Name',
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
