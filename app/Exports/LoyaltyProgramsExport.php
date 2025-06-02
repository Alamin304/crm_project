<?php

namespace App\Exports;

use App\Models\LoyaltyProgram;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LoyaltyProgramsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    public function collection()
    {
        return LoyaltyProgram::orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            __('messages.loyalty_programs.name'),
            __('messages.loyalty_programs.redeem_type'),
            __('messages.loyalty_programs.start_date'),
            __('messages.loyalty_programs.end_date'),
            __('messages.loyalty_programs.minimum_point_to_redeem'),
            __('messages.loyalty_programs.rule_base'),
            __('messages.loyalty_programs.minimum_purchase'),
        ];
    }

    public function map($program): array
    {
        return [
            $program->name,
            ucfirst($program->redeem_type),
            $program->start_date ? Carbon::parse($program->start_date)->format('Y-m-d') : '',
            $program->end_date ? Carbon::parse($program->end_date)->format('Y-m-d') : '',
            $program->minimum_point_to_redeem,
            ucfirst($program->rule_base),
            $program->minimum_purchase,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER,        // minimum_point_to_redeem
            'G' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE, // minimum_purchase (adjust currency as needed)
        ];
    }
}
