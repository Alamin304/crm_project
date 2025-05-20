<?php

namespace App\Imports;

use App\Models\Shift;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ShiftImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Shift([
            'name' => $row['name'],
            'shift_start_time' => $row['shift_start_time'],
            'shift_end_time' => $row['shift_end_time'],
            'description' => $row['description'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'max:255', 'unique:shifts,name'],
            '*.shift_start_time' => ['required', 'date_format:H:i'],
            '*.shift_end_time' => ['required', 'date_format:H:i', 'after:*.shift_start_time'],
            '*.description' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.name.required' => 'The shift name field is required.',
            '*.name.string' => 'The shift name must be a string.',
            '*.name.max' => 'The shift name may not be greater than 255 characters.',
            '*.name.unique' => 'The shift name already exists.',
            '*.shift_start_time.required' => 'The shift start time field is required.',
            '*.shift_start_time.date_format' => 'The shift start time must be in HH:MM format (24-hour).',
            '*.shift_end_time.required' => 'The shift end time field is required.',
            '*.shift_end_time.date_format' => 'The shift end time must be in HH:MM format (24-hour).',
            '*.shift_end_time.after' => 'The shift end time must be after the shift start time.',
            '*.description.string' => 'The description must be a string.',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}
