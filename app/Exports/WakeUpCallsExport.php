<?php

namespace App\Exports;

use App\Models\WakeUpCall;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class WakeUpCallsExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    public function collection()
    {
        return WakeUpCall::query()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function map($call): array
    {
        static $index = 0;
        $index++;

        return [
            $index, // Serial number instead of $call->id
            $call->customer_name,
            $call->date ? Date::dateTimeToExcel(Carbon::parse($call->date)) : null,
            $this->cleanDescription($call->description),
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.wake_up_calls.id'), // Update this translation key
            __('messages.wake_up_calls.customer_name'),
            __('messages.wake_up_calls.date'),
            __('messages.wake_up_calls.description'),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

    protected function cleanDescription($description): string
    {
        $cleaned = strip_tags($description);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        return trim($cleaned);
    }
}
