<?php

namespace App\Imports;

use App\Models\Complementary;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ComplementaryImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Complementary([
            'room_type' => $row['room_type'],
            'complementary' => $row['complementary'],
            'rate' => $row['rate'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.room_type' => ['required', 'string', 'max:255'],
            '*.complementary' => ['required', 'string', 'max:255'],
            '*.rate' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.room_type.required' => 'The room type field is required.',
            '*.room_type.string' => 'The room type must be a string.',
            '*.room_type.max' => 'The room type may not be greater than 255 characters.',
            '*.complementary.required' => 'The complementary field is required.',
            '*.complementary.string' => 'The complementary must be a string.',
            '*.complementary.max' => 'The complementary may not be greater than 255 characters.',
            '*.rate.required' => 'The rate field is required.',
            '*.rate.numeric' => 'The rate must be a number.',
            '*.rate.min' => 'The rate must be at least 0.',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}
