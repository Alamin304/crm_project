<?php

namespace App\Exports;

use App\Models\BillsOfMaterial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BillsOfMaterialExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return BillsOfMaterial::all();
    }

    public function headings(): array
    {
        return [
            'BOM Code',
            'Product',
            'Product Variant',
            'Quantity',
            'Unit of Measure',
            'Routing',
            'BOM Type',
            'Manufacturing Readiness',
            'Consumption',
            'Created At'
        ];
    }

    public function map($bom): array
    {
        return [
            $bom->BOM_code,
            $bom->product,
            $bom->product_variant,
            $bom->quantity,
            $bom->unit_of_measure,
            $bom->routing,
            $bom->bom_type == 'manufacture' ? 'Manufacture this product' : 'Kit',
            $bom->manufacturing_readiness,
            $bom->consumption,
            $bom->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
