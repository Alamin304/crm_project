<?php

// app/Imports/NoticeBoardImport.php
namespace App\Imports;

use App\Models\NoticeBoard;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class NoticeBoardImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        return new NoticeBoard([
            'notice_type' => $row['notice_type'],
            'description' => $row['description'],
            'notice_date' => $row['notice_date'],
            'notice_by' => $row['notice_by'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.notice_type' => 'required|string|max:255',
            '*.description' => 'required|string',
            '*.notice_date' => 'required|date',
            '*.notice_by' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.notice_type.required' => 'The notice type field is required.',
            '*.description.required' => 'The description field is required.',
            '*.notice_date.required' => 'The notice date field is required.',
            '*.notice_date.date' => 'The notice date must be a valid date.',
            '*.notice_by.required' => 'The notice by field is required.',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}
