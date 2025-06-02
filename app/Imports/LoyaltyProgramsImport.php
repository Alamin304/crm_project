<?php

namespace App\Imports;

use App\Models\LoyaltyProgram;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LoyaltyProgramsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new LoyaltyProgram([
            'name' => $row['name'],
            'customer_group' => $row['customer_group'],
            'customer' => $row['customer'],
            'start_date' => $row['start_date'],
            'end_date' => $row['end_date'],
            'description' => $row['description'] ?? null,
            'rule_base' => $row['rule_base'],
            'minimum_purchase' => $row['minimum_purchase'],
            'account_creation_point' => $row['account_creation_point'],
            'birthday_point' => $row['birthday_point'],
            'redeem_type' => $row['redeem_type'],
            'minimum_point_to_redeem' => $row['minimum_point_to_redeem'],
            'max_amount_receive' => $row['max_amount_receive'],
            'redeem_in_portal' => $row['redeem_in_portal'] ?? false,
            'redeem_in_pos' => $row['redeem_in_pos'] ?? false,
            'status' => $row['status'],
            'rules' => isset($row['rules']) ? json_decode($row['rules'], true) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'customer_group' => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'rule_base' => 'required|string|max:255',
            'minimum_purchase' => 'required|numeric|min:0',
            'account_creation_point' => 'required|integer|min:0',
            'birthday_point' => 'required|integer|min:0',
            'redeem_type' => 'required|string|max:255',
            'minimum_point_to_redeem' => 'required|integer|min:0',
            'max_amount_receive' => 'required|numeric|min:0',
            'status' => 'required|in:enabled,disabled',
        ];
    }
}
