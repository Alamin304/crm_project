<?php

namespace App\Imports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class BranchImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Branch([
            'company_name' => $row['company_name'],
            'name' => $row['name'],
            'website' => $row['website'] ?? null,
            'vat_number' => $row['vat_number'] ?? null,
            'currency_id' => $row['currency_id'] ?? null,
            'city' => $row['city'] ?? null,
            'state' => $row['state'] ?? null,
            'country_id' => $row['country_id'] ?? null,
            'zip_code' => $row['zip_code'] ?? null,
            'phone' => $row['phone'] ?? null,
            'address' => $row['address'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.company_name' => ['required', 'integer', 'max:255'],
            '*.name' => ['required', 'string', 'max:255'],
            '*.website' => ['nullable', 'string', 'max:255'],
            '*.vat_number' => ['nullable', 'string', 'max:255'],
            '*.currency_id' => ['nullable', 'integer', 'exists:currencies,id'],
            '*.city' => ['nullable', 'string', 'max:255'],
            '*.state' => ['nullable', 'string', 'max:255'],
            '*.country_id' => ['nullable', 'integer', 'exists:countries,id'],
            '*.zip_code' => ['nullable', 'string', 'max:255'],
            '*.phone' => ['nullable', 'string', 'max:255'],
            '*.address' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.company_name.required' => 'Company name is required',
            '*.name.required' => 'Branch name is required',
            '*.currency_id.exists' => 'The selected currency does not exist',
            '*.country_id.exists' => 'The selected country does not exist',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}