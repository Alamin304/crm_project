<?php

namespace App\Exports;

use App\Models\Bank;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BanksExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Bank::orderBy('name')->get();
    }

    public function map($bank): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $this->cleanHtml($bank->name),
            $this->cleanHtml($bank->account_number ?? ''),
            $this->cleanHtml($bank->branch_name ?? ''),
            $bank->iban_number ?? '',
            $this->cleanHtml($bank->description),
            number_format($bank->opening_balance, 2),
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.banks.id'),
            __('messages.banks.name'),
            __('messages.banks.account_number'),
            __('messages.banks.branch_name'),
            __('messages.banks.iban_number'),
            __('messages.banks.description'),
            __('messages.banks.opening_balance'),
        ];
    }

    protected function cleanHtml($content): string
    {
        $cleaned = strip_tags($content);
        return trim(preg_replace('/\s+/', ' ', $cleaned));
    }
}
