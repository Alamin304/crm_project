<?php

namespace App\Imports;

use App\Models\Bank;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class BankImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Bank([
            'name' => $row['name'],
            'account_number' => $row['account_number'],
            'branch_name' => $row['branch_name'] ?? null,
            'swift_code' => $row['swift_code'] ?? null,
            'description' => $row['description'] ?? null,
            'opening_balance' => $row['opening_balance'] ?? 0,
            'iban_number' => $row['iban_number'] ?? null,
            'address' => $row['address'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'max:255'],
            // '*.account_number' => ['required', 'numeric', 'max:255', 'unique:banks,account_number'],
            '*.branch_name' => ['nullable', 'string', 'max:255'],
            '*.swift_code' => ['nullable', 'string', 'max:255'],
            '*.description' => ['nullable', 'string'],
            '*.opening_balance' => ['nullable', 'numeric', 'min:0'],
            '*.iban_number' => ['nullable', 'string', 'max:255'],
            '*.address' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.name.required' => 'Bank name is required',
            '*.account_number.required' => 'Account number is required',
            '*.account_number.unique' => 'This account number already exists',
            '*.opening_balance.numeric' => 'Opening balance must be a number',
            '*.opening_balance.min' => 'Opening balance cannot be negative',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}