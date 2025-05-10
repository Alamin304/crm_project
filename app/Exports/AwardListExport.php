<?php

namespace App\Exports;

use App\Models\AwardList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AwardListExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return AwardList::query()
            ->orderBy('id')
            ->get();
    }

    public function map($award): array
    {
        return [
            $award->id,
            $award->award_name,
            $award->award_description,
            $award->gift_item,
            $award->date,
            $award->employee_name,
            $award->award_by,
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.award_lists.id'), // SL
            __('messages.award_lists.award_name'), // Award Name
            __('messages.award_lists.award_description'), // Award Description
            __('messages.award_lists.gift_item'), // Gift Item
            __('messages.award_lists.date'), // Date
            __('messages.award_lists.employee_name'), // Employee Name
            __('messages.award_lists.award_by'), // Award By
        ];
    }
}
