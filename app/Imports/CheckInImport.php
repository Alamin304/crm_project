<?php

namespace App\Imports;

use App\Models\CheckIn;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CheckInImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new CheckIn([
            'booking_number' => $row['booking_number'],
            'check_in' => Carbon::parse($row['check_in']),
            'check_out' => Carbon::parse($row['check_out']),
            'arrival_from' => $row['arrival_from'] ?? null,
            'booking_type' => $row['booking_type'] ?? null,
            'booking_reference' => $row['booking_reference'] ?? null,
            'booking_reference_no' => $row['booking_reference_no'] ?? null,
            'visit_purpose' => $row['visit_purpose'] ?? null,
            'remarks' => $row['remarks'] ?? null,
            'room_type' => $row['room_type'] ?? null,
            'room_no' => $row['room_no'] ?? null,
            'adults' => $row['adults'] ?? 1,
            'children' => $row['children'] ?? 0,
            'booking_status' => $row['booking_status'] ?? true,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.booking_number' => ['required', 'string', 'max:255', 'unique:check_ins,booking_number'],
            '*.check_in' => ['required', 'date_format:Y-m-d H:i'],
            '*.check_out' => ['required', 'date_format:Y-m-d H:i', 'after:*.check_in'],
            '*.arrival_from' => ['nullable', 'string', 'max:255'],
            '*.booking_type' => ['nullable', 'string', 'max:255'],
            '*.booking_reference' => ['nullable', 'string', 'max:255'],
            '*.booking_reference_no' => ['nullable', 'string', 'max:255'],
            '*.visit_purpose' => ['nullable', 'string', 'max:255'],
            '*.remarks' => ['nullable', 'string'],
            '*.room_type' => ['nullable', 'string', 'max:255'],
            '*.room_no' => ['nullable', 'integer', 'max:255'],
            '*.adults' => ['nullable', 'integer', 'min:1'],
            '*.children' => ['nullable', 'integer', 'min:0'],
            '*.booking_status' => ['nullable', 'boolean'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.booking_number.required' => 'Booking number is required',
            '*.booking_number.unique' => 'This booking number already exists',
            '*.check_in.required' => 'Check-in date/time is required',
            '*.check_in.date_format' => 'Check-in must be in YYYY-MM-DD HH:MM format',
            '*.check_out.required' => 'Check-out date/time is required',
            '*.check_out.date_format' => 'Check-out must be in YYYY-MM-DD HH:MM format',
            '*.check_out.after' => 'Check-out must be after check-in',
            '*.adults.integer' => 'Adults must be a number',
            '*.adults.min' => 'At least 1 adult is required',
            '*.children.integer' => 'Children must be a number',
            '*.children.min' => 'Children cannot be negative',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}