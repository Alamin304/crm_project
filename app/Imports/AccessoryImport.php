<?php

namespace App\Imports;

use App\Models\Accessory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AccessoryImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Accessory([
            'accessory_name' => $row['accessory_name'],
            'category_name' => $row['category_name'],
            'supplier' => $row['supplier'],
            'manufacturer' => $row['manufacturer'],
            'location' => $row['location'],
            'model_number' => $row['model_number'],
            'order_number' => $row['order_number'],
            'purchase_cost' => $row['purchase_cost'],
            'purchase_date' => $row['purchase_date'],
            'quantity' => $row['quantity'],
            'min_quantity' => $row['min_quantity'],
            'for_sell' => $row['for_sell'],
            'selling_price' => $row['selling_price'],
            'notes' => $row['notes'],
        ]);
    }
}
