<?php

namespace App\Exports;

use App\Models\License;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LicensesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return License::all();
    }

    public function headings(): array
    {
        return [
            'Software Name',
            'Category',
            'Product Key',
            'Seats',
            'Manufacturer',
            'Licensed Name',
            'Licensed Email',
            'Reassignable',
            'Supplier',
            'Order Number',
            'Purchase Order Number',
            'Purchase Cost',
            'Purchase Date',
            'Expiration Date',
            'Termination Date',
            'Depreciation',
            'Maintained',
            'For Sell',
            'Selling Price',
            'Notes'
        ];
    }

    public function map($license): array
    {
        return [
            $license->software_name,
            $license->category_name,
            $license->product_key,
            $license->seats,
            $license->manufacturer,
            $license->licensed_name,
            $license->licensed_email,
            $license->reassignable ? 'Yes' : 'No',
            $license->supplier,
            $license->order_number,
            $license->purchase_order_number,
            $license->purchase_cost,
            $license->purchase_date->format('Y-m-d'),
            $license->expiration_date ? $license->expiration_date->format('Y-m-d') : '',
            $license->termination_date ? $license->termination_date->format('Y-m-d') : '',
            $license->depreciation,
            $license->maintained ? 'Yes' : 'No',
            $license->for_sell ? 'Yes' : 'No',
            $license->selling_price,
            $license->notes,
        ];
    }
}