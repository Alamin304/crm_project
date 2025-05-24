<?php

namespace App\Exports;

use App\Models\Campaign;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CampaignsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Campaign::select([
            'campaign_name',
            'company',
            'position',
            'working_form',
            'department',
            'recruitment_plan',
            'recruited_quantity',
            'recruitment_channel_from',
            'managers',
            'is_active',
        ])->get();
    }

    public function headings(): array
    {
        return [
            __('messages.campaigns.campaign_name'),
            __('messages.campaigns.company'),
            __('messages.campaigns.position'),
            __('messages.campaigns.working_form'),
            __('messages.campaigns.department'),
            __('messages.campaigns.recruitment_plan'),
            __('messages.campaigns.recruited_quantity'),
            __('messages.campaigns.recruitment_channel_from'),
            __('messages.campaigns.managers'),
            __('messages.campaigns.is_active'),
        ];
    }

    public function map($campaign): array
    {
        return [
            $campaign->campaign_name,
            $campaign->company,
            $campaign->position,
            $campaign->working_form,
            $campaign->department,
            $campaign->recruitment_plan,
            $campaign->recruited_quantity,
            $campaign->recruitment_channel_from,
            is_array($campaign->managers) ? implode(', ', $campaign->managers) : $campaign->managers,
            $campaign->is_active ? __('messages.campaigns.active') : __('messages.campaigns.inactive'),
        ];
    }
}
