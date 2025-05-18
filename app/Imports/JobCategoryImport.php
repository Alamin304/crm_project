<?php

namespace App\Imports;

use App\Models\JobCategory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithValidation;

class JobCategoryImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new JobCategory([
            'name' => $row['name'],
            'description' => $row['description'] ?? '',
            'start_date' => $row['startdate'],
            'end_date' => $row['enddate'],
            'status' => 1,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string', 'max:255'],
            '*.description' => ['nullable', 'string'],
            '*.startdate' => ['required', 'date'],
            '*.enddate' => ['required', 'date', 'after_or_equal:*.startdate'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.name.required' => 'The name field is required.',
            '*.startdate.required' => 'The start date is required.',
            '*.startdate.date' => 'The start date must be a valid date.',
            '*.enddate.required' => 'The end date is required.',
            '*.enddate.date' => 'The end date must be a valid date.',
            '*.enddate.after_or_equal' => 'The end date must be after or equal to the start date.',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}

