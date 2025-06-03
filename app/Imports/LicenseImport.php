<?php

namespace App\Imports;

use App\Models\License;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;

class LicenseImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        return new License([
            'software_name' => $row['software_name'],
            'category_name' => $row['category_name'],
            'product_key' => $row['product_key'],
            'seats' => $row['seats'],
            'manufacturer' => $row['manufacturer'],
            'licensed_name' => $row['licensed_name'],
            'licensed_email' => $row['licensed_email'],
            'reassignable' => strtolower($row['reassignable']) === 'yes' ? 1 : 0,
            'supplier' => $row['supplier'],
            'order_number' => $row['order_number'],
            'purchase_order_number' => $row['purchase_order_number'],
            'purchase_cost' => $row['purchase_cost'],
            'purchase_date' => $row['purchase_date'],
            'expiration_date' => $row['expiration_date'] ?: null,
            'termination_date' => $row['termination_date'] ?: null,
            'depreciation' => $row['depreciation'],
            'maintained' => strtolower($row['maintained']) === 'yes' ? 1 : 0,
            'for_sell' => strtolower($row['for_sell']) === 'yes' ? 1 : 0,
            'selling_price' => $row['selling_price'] ?: null,
            'notes' => $row['notes'] ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'software_name' => 'required|string|max:255',
            'category_name' => 'required|string|max:255',
            'product_key' => 'required|string|max:255',
            'seats' => 'required|integer|min:1',
            'manufacturer' => 'required|string|max:255',
            'licensed_name' => 'required|string|max:255',
            'licensed_email' => 'required|email|max:255',
            'reassignable' => 'required',
            'supplier' => 'required|string|max:255',
            'order_number' => 'required|string|max:255',
            'purchase_order_number' => 'required|string|max:255',
            'purchase_cost' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'expiration_date' => 'nullable|date',
            'termination_date' => 'nullable|date',
            'depreciation' => 'required|string|max:255',
            'maintained' => 'required',
            'for_sell' => 'required',
            'selling_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}