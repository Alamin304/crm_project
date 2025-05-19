<?php

namespace App\Imports;

use App\Models\Group;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class GroupImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Group([
            'group_name'  => $row['group_name'],
            'description' => $row['description'] ?? '',
        ]);
    }

    public function rules(): array
    {
        return [
            '*.group_name' => ['required', 'string', 'max:255'],
            '*.description' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.group_name.required' => 'The group name field is required.',
        ];
    }

    public function failures()
    {
        return $this->failures;
    }
}

