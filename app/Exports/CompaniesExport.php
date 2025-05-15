<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CompaniesExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Company::orderBy('name', 'asc')->get();
    }

    public function map($Company): array
    {
        static $index = 0;
        $index++;
        return [
            $index,
            $Company->name,
            $this->cleanDescription($Company->description),
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.companies.id'),
            __('messages.companies.name'),
            __('messages.companies.description'),
        ];
    }

    protected function cleanDescription($description): string
    {
        $cleaned = strip_tags($description);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        return trim($cleaned);
    }
}
