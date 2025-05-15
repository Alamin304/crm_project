<?php

namespace App\Exports;

use App\Models\Division;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DivisionsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Division::orderBy('name', 'asc')->get();
    }

    public function map($division): array
    {
        static $index = 0;
        $index++;
        return [
            $index,
            $division->name,
            $this->cleanDescription($division->description),
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.divisions.id'),
            __('messages.divisions.name'),
            __('messages.divisions.description'),
        ];
    }

    protected function cleanDescription($description): string
    {
        $cleaned = strip_tags($description);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        return trim($cleaned);
    }
}
