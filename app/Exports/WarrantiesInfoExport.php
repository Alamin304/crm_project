<?php
namespace App\Exports;

use App\Models\Warranty;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WarrantiesInfoExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
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
            $warranty->customer ?? '-',
            '-', // Order Number (placeholder)
            $warranty->invoice ?? '-',
            $warranty->product_service_name ?? '-',
            '-', // Rate (placeholder)
            '-', // Quantity (placeholder)
            '-', // Serial Number (placeholder)
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.warranties.id'),
            __('messages.warranties.customer'),
            __('messages.warranties.order_number'),
            __('messages.warranties.invoice'),
            __('messages.warranties.product_service_name'),
            __('messages.warranties.rate'),
            __('messages.warranties.quantity'),
            __('messages.warranties.serial_number'),
        ];
    }
}
