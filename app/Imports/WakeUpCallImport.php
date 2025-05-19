<?php

namespace App\Imports;

use App\Models\WakeUpCall;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class WakeUpCallImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new WakeUpCall([
            'customer_name' => $row['customer_name'],
            'date' => $row['date'],
            'description' => $row['description'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.customer_name' => ['required', 'string', 'max:255'],
            '*.date' => ['required', 'date_format:Y-m-d H:i'],
            '*.description' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.customer_name.required' => 'The customer name field is required.',
            '*.customer_name.string' => 'The customer name must be a string.',
            '*.customer_name.max' => 'The customer name may not be greater than 255 characters.',
            '*.date.required' => 'The date field is required.',
            '*.date.date_format' => 'The date must be in the format YYYY-MM-DD HH:MM.',
            '*.description.string' => 'The description must be a string.',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}
