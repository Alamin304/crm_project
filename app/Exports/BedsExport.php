<?php

namespace App\Exports;

use App\Models\Bed;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BedsExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    public function collection()
    {
        return Bed::query()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function map($bed): array
    {
        return [
            $bed->name,
            $this->cleanDescription($bed->description),
            "'" . $bed->created_at->format('Y-m-d H:i:s'),
        ];
    }
    public function headings(): array
    {
        return [
            __('messages.beds.name'),
            __('messages.beds.description'),
            __('Created At'),
        ];
    }

    public function columnFormats(): array
    {
        return []; 
    }

    protected function cleanDescription($description): string
    {
        // Remove HTML tags and trim whitespace
        $cleaned = strip_tags($description);

        // Replace multiple spaces/newlines with single space
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);

        // Trim and return
        return trim($cleaned);
    }
}
