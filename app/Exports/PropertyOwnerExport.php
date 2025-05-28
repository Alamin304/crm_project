<?php

namespace App\Exports;

use App\Models\PropertyOwner;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PropertyOwnerExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    public function collection()
    {
        return PropertyOwner::orderBy('created_at', 'desc')->get();
    }

    public function map($owner): array
    {
        return [
            $owner->code,
            $owner->owner_name,
            $owner->email,
            $owner->phone_number,
            $owner->is_active ? 'Active' : 'Inactive',
            $owner->created_at ? Date::dateTimeToExcel(Carbon::parse($owner->created_at)) : null,
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
