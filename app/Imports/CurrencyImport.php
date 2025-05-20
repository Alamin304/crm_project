<?php

// CurrencyImport.php
namespace App\Imports;

use App\Models\Currency;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CurrencyImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        return new Currency([
            'name'        => $row['name'],
            'description' => $row['description'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255|unique:currencies,name',
            '*.description' => 'nullable|string|max:1000',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.name.required' => 'The currency name field is required.',
            '*.name.unique' => 'The currency name already exists.',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}
