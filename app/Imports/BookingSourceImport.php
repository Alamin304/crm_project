<?php

namespace App\Imports;

use App\Models\BookingSource;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class BookingSourceImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new BookingSource([
            'booking_type' => $row['booking_type'],
            'booking_source' => $row['booking_source'],
            'commission_rate' => $row['commission_rate'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.booking_type' => ['required', 'string', 'max:255'],
            '*.booking_source' => ['required', 'string', 'max:255'],
            '*.commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.booking_type.required' => 'The booking type field is required.',
            '*.booking_type.string' => 'The booking type must be a string.',
            '*.booking_type.max' => 'The booking type may not be greater than 255 characters.',
            '*.booking_source.required' => 'The booking source field is required.',
            '*.booking_source.string' => 'The booking source must be a string.',
            '*.booking_source.max' => 'The booking source may not be greater than 255 characters.',
            '*.commission_rate.required' => 'The commission rate field is required.',
            '*.commission_rate.numeric' => 'The commission rate must be a number.',
            '*.commission_rate.min' => 'The commission rate must be at least 0.',
            '*.commission_rate.max' => 'The commission rate must not be greater than 100.',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}
