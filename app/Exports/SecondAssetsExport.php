<?php

namespace App\Exports;

use App\Models\SecondAsset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SecondAssetsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return SecondAsset::orderBy('asset_name', 'asc')->get();
    }

    public function map($asset): array
    {
        return [
            $asset->asset_name,
            $asset->serial_number,
            $asset->model,
            $asset->status,
            $asset->location,
            $asset->supplier,
            $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '',
            $asset->purchase_cost,
            $asset->order_number,
            $asset->warranty,
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.second_assets.asset_name'),
            __('messages.second_assets.serial_number'),
            __('messages.second_assets.model'),
            __('messages.second_assets.status'),
            __('messages.second_assets.location'),
            __('messages.second_assets.supplier'),
            __('messages.second_assets.purchase_date'),
            __('messages.second_assets.purchase_cost'),
            __('messages.second_assets.order_number'),
            __('messages.second_assets.warranty'),
        ];
    }
}
