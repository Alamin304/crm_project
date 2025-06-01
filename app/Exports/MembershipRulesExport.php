<?php

namespace App\Exports;

use App\Models\MembershipRule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MembershipRulesExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return MembershipRule::orderBy('created_at', 'desc')->get();
    }

    public function map($membershipRule): array
    {
        return [
            $membershipRule->name,
            $membershipRule->customer_group,
            $membershipRule->customer,
            $membershipRule->card,
            $membershipRule->point_from,
            $membershipRule->point_to,
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.membership_rules.name'),
            __('messages.membership_rules.customer_group'),
            __('messages.membership_rules.customer'),
            __('messages.membership_rules.card'),
            __('messages.membership_rules.point_from'),
            __('messages.membership_rules.point_to'),
        ];
    }
}
