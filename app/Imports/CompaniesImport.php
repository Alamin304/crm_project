<?php

namespace App\Imports;

use App\Models\Company;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CompaniesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        return new Company([
            'name'        => $row['name'],
            'description' => $row['description'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255|unique:divisions,name',
            '*.description' => 'nullable|string|max:1000',
        ];
    }


    public function customValidationMessages(): array
    {
        return [
            '*.name.required' => 'The Company name field is required.',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}

