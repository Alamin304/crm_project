<?php

namespace App\Exports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BranchesExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Branch::with(['country', 'bank'])->orderBy('name')->get();
    }

    public function map($branch): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $this->cleanHtml($branch->company_name ?? ''),
            $this->cleanHtml($branch->name),
            $branch->vat_number ?? '',
            $branch->phone ?? '',
            optional($branch->country)->name ?? '',
            $branch->city ?? '',
            optional($branch->bank)->name ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.branches.id'),
            __('messages.branches.company'),
            __('messages.branches.name'),
            __('messages.customer.vat_number'),
            __('messages.branches.phone'),
            __('messages.suppliers.country'),
            __('messages.customer.city'),
            __('messages.banks.name'),
        ];
    }

    protected function cleanHtml($content): string
    {
        $cleaned = strip_tags($content ?? '');
        return trim(preg_replace('/\s+/', ' ', $cleaned));
    }
}
