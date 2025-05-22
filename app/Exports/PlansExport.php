<?php

namespace App\Exports;

use App\Models\Plan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PlansExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // only select the columns you need
        return Plan::select([
            'plan_name',
            'position',
            'working_form',
            'department',
            'recruited_quantity',
            'is_active',
        ])->get();
    }

    public function headings(): array
    {
        return [
            __('messages.plans.plan_name'),
            __('messages.plans.position'),
            __('messages.plans.working_form'),
            __('messages.plans.department'),
            __('messages.plans.recruited_quantity'),
            __('messages.plans.is_active'),
        ];
    }

    public function map($plan): array
    {
        return [
            $plan->plan_name,
            $plan->position,
            $plan->working_form,
            $plan->department,
            $plan->recruited_quantity,
            $plan->is_active ? __('messages.plans.active') : __('messages.plans.inactive'),
        ];
    }
}
