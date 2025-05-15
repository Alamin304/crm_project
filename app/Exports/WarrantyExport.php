<?php

namespace App\Exports;

use App\Models\Warranty;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class WarrantyExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    public function collection()
    {
        return Warranty::query()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function map($warranty): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $warranty->claim_code,
            $warranty->customer,
            $warranty->date_created ? Date::dateTimeToExcel(Carbon::parse($warranty->date_created)) : null,
            $this->cleanDescription($warranty->description),
            $warranty->status,
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.warranties.id'),
            __('messages.warranties.claim_code'),
            __('messages.warranties.customer'),
            __('messages.warranties.date_created'),
            __('messages.warranties.description'),
            __('messages.warranties.status'),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
        ];
    }

    protected function cleanDescription($description): string
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags($description)));
    }
}
