<?php

namespace App\Imports;

use App\Models\AwardList;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class AwardListImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new AwardList([
            'award_name' => $row['award_name'],
            'award_description' => $row['award_description'] ?? null,
            'gift_item' => $row['gift_item'] ?? null,
            'date' => $row['date'],
            'employee_name' => $row['employee_name'],
            'award_by' => $row['award_by'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.award_name' => ['required', 'string', 'max:255'],
            '*.award_description' => ['nullable', 'string'],
            '*.gift_item' => ['nullable', 'string', 'max:255'],
            '*.date' => ['required', 'date'],
            '*.employee_name' => ['required', 'string', 'max:255'],
            '*.award_by' => ['required', 'string', 'max:255'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.award_name.required' => 'The award name field is required.',
            '*.award_name.string' => 'The award name must be a string.',
            '*.award_name.max' => 'The award name may not be greater than 255 characters.',
            '*.award_description.string' => 'The award description must be a string.',
            '*.gift_item.string' => 'The gift item must be a string.',
            '*.gift_item.max' => 'The gift item may not be greater than 255 characters.',
            '*.date.required' => 'The date field is required.',
            '*.date.date' => 'The date must be a valid date.',
            '*.employee_name.required' => 'The employee name field is required.',
            '*.employee_name.string' => 'The employee name must be a string.',
            '*.employee_name.max' => 'The employee name may not be greater than 255 characters.',
            '*.award_by.required' => 'The awarded by field is required.',
            '*.award_by.string' => 'The awarded by must be a string.',
            '*.award_by.max' => 'The awarded by may not be greater than 255 characters.',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}
