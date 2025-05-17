<?php

namespace App\Exports;

use App\Models\Currency;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CurrenciesExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Currency::orderBy('name')->get();
    }

    public function map($currency): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $currency->name,
            $currency->description,
            $currency->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.currencies.id'),
            __('messages.currencies.currencies'),
            __('messages.currencies.description'),
            __('messages.currencies.created_at'),
        ];
    }
}
