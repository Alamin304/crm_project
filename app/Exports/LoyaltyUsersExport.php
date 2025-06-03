<?php

namespace App\Exports;

use App\Models\LoyaltyUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LoyaltyUsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    public function collection()
    {
        return LoyaltyUser::orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            __('messages.loyalty_users.customer'),
            __('messages.loyalty_users.email'),
            __('messages.loyalty_users.membership'),
            __('messages.loyalty_users.loyalty_point'),
        ];
    }

    public function map($user): array
    {
        return [
            $user->customer,
            $user->email,
            $user->membership,
            $user->loyalty_point,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER, // loyalty_point
        ];
    }
}
