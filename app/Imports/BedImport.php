<?php

namespace App\Imports;

use App\Models\Bed;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Validation\Rule;

class BedImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Bed([
            'name' => $row['name'],
            'description' => $row['description'] ?? '',
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'max:255'],
            '*.description' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.name.required' => 'The bed name field is required.',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}

