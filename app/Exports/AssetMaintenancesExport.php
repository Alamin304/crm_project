<?php

namespace App\Exports;

use App\Models\AssetMaintenance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetMaintenancesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return AssetMaintenance::with(['asset', 'supplier'])->get();
    }

    public function headings(): array
    {
        return [
            'Asset',
            'Supplier',
            'Maintenance Type',
            'Title',
            'Start Date',
            'Completion Date',
            'Warranty Improvement',
            'Cost',
            'Notes'
        ];
    }

    public function map($assetMaintenance): array
    {
        return [
            $assetMaintenance->asset->name,
            $assetMaintenance->supplier->name,
            $assetMaintenance->maintenance_type,
            $assetMaintenance->title,
            $assetMaintenance->start_date->format('Y-m-d'),
            $assetMaintenance->completion_date ? $assetMaintenance->completion_date->format('Y-m-d') : 'N/A',
            $assetMaintenance->warranty_improvement ? 'Yes' : 'No',
            $assetMaintenance->cost ?? '0.00',
            $assetMaintenance->notes ?? 'N/A'
        ];
    }
}
