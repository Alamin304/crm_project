<?php

namespace App\Imports;

use App\Models\MembershipRule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MembershipRulesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new MembershipRule([
            'name' => $row['name'],
            'customer_group' => $row['customer_group'],
            'customer' => $row['customer'],
            'card' => $row['card'],
            'point_from' => $row['point_from'],
            'point_to' => $row['point_to'],
            'description' => $row['description'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'customer_group' => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'card' => 'required|string|max:255',
            'point_from' => 'required|integer|min:0',
            'point_to' => 'required|integer|gt:point_from',
            'description' => 'nullable|string',
        ];
    }
}
