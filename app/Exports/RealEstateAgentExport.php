<?php

namespace App\Exports;

use App\Models\RealEstateAgent;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class RealEstateAgentExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return RealEstateAgent::orderBy('created_at', 'desc')->get();
    }

    public function map($agents): array
    {
        return [
            $agents->code,
            $agents->owner_name,
            $agents->email,
            $agents->phone_number,
            $agents->is_active ? 'Active' : 'Inactive',
            $agents->created_at ? Date::dateTimeToExcel(Carbon::parse($agents->created_at)) : null,
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.property_owners.code'),
            __('messages.property_owners.name'),
            __('messages.property_owners.email'),
            __('messages.property_owners.phone'),
            __('messages.property_owners.is_active'),
            __('messages.property_owners.created_at'),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD2, // created_at
        ];
    }
}
