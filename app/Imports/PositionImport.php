<?php

namespace App\Imports;

use App\Models\Position;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class PositionImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Position([
            'name' => $row['name'],
            'details' => $row['details'] ?? null,
            'status' => $row['status'] == '1' ? 1 : 0,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'max:255', 'unique:positions,name'],
            '*.details' => ['nullable', 'string'],
            '*.status' => ['required', 'in:0,1'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.name.required' => 'The position name field is required.',
            '*.name.string' => 'The position name must be a string.',
            '*.name.max' => 'The position name may not be greater than 255 characters.',
            '*.name.unique' => 'The position name already exists.',
            '*.details.string' => 'The details must be a string.',
            '*.status.required' => 'The status field is required.',
            '*.status.in' => 'The status must be either 0 (inactive) or 1 (active).',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}
