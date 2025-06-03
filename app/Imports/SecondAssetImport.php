<?php

namespace App\Imports;

use App\Models\SecondAsset;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class SecondAssetImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        return new SecondAsset([
            'asset_name' => $row['asset_name'],
            'serial_number' => $row['serial_number'],
            'model' => $row['model'],
            'status' => $row['status'],
            'location' => $row['location'],
            'supplier' => $row['supplier'],
            'purchase_date' => $row['purchase_date'],
            'purchase_cost' => $row['purchase_cost'],
            'order_number' => $row['order_number'],
            'warranty' => $row['warranty'],
            'requestable' => $row['requestable'] ?? false,
            'for_sell' => $row['for_sell'] ?? false,
            'selling_price' => $row['selling_price'] ?? null,
            'for_rent' => $row['for_rent'] ?? false,
            'rental_price' => $row['rental_price'] ?? null,
            'minimum_renting_price' => $row['minimum_renting_price'] ?? null,
            'unit' => $row['unit'] ?? null,
            'description' => $row['description'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.serial_number' => 'required|string|unique:second_assets,serial_number|max:255',
            '*.asset_name' => 'required|string|max:255',
            '*.model' => 'required|string|max:255',
            '*.status' => 'required|string|in:ready,pending,undeployable,archived,operational,non-operational,repairing',
            '*.supplier' => 'required|string|max:255',
            '*.purchase_date' => 'required|date',
            '*.order_number' => 'required|string|max:255',
            '*.purchase_cost' => 'required|numeric|min:0',
            '*.location' => 'required|string|max:255',
            '*.warranty' => 'required|integer|min:0',
            '*.requestable' => 'boolean',
            '*.for_sell' => 'boolean',
            '*.selling_price' => 'nullable|required_if:*.for_sell,true|numeric|min:0',
            '*.for_rent' => 'boolean',
            '*.rental_price' => 'nullable|required_if:*.for_rent,true|numeric|min:0',
            '*.minimum_renting_price' => 'nullable|required_if:*.for_rent,true|numeric|min:0',
            '*.unit' => 'nullable|required_if:*.for_rent,true|string|max:50',
            '*.description' => 'nullable|string',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.serial_number.required' => 'Serial number is required.',
            '*.asset_name.required' => 'Asset name is required.',
            '*.status.in' => 'Status must be one of: ready, pending, undeployable, archived, operational, non-operational, repairing.',
            // Add more custom messages as needed
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}
