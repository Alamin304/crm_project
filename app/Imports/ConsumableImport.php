<?php

namespace App\Imports;

use App\Models\Consumable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ConsumableImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Consumable([
            'consumable_name' => $row['consumable_name'],
            'category_name' => $row['category_name'],
            'supplier' => $row['supplier'],
            'manufacturer' => $row['manufacturer'],
            'location' => $row['location'] ?? null,
            'model_number' => $row['model_number'] ?? null,
            'order_number' => $row['order_number'] ?? null,
            'purchase_cost' => $row['purchase_cost'],
            'purchase_date' => $row['purchase_date'],
            'quantity' => $row['quantity'],
            'min_quantity' => $row['min_quantity'] ?? 0,
            'for_sell' => strtolower($row['for_sell']) === 'yes' || $row['for_sell'] === '1',
            'selling_price' => $row['selling_price'] ?? null,
            'notes' => $row['notes'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'consumable_name' => 'required|string|max:255',
            'category_name' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'purchase_cost' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'nullable|integer|min:0',
            'for_sell' => 'nullable|string',
            'selling_price' => 'nullable|numeric|min:0',
        ];
    }
}
