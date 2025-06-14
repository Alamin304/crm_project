<?php

namespace App\Exports;

use App\Models\WorkCenter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WorkCentersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return WorkCenter::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Code',
            'Working Hours',
            'Time Efficiency (%)',
            'Cost Per Hour',
            'Capacity',
            'OEE Target (%)',
            'Time Before Prod (m)',
            'Time After Prod (m)',
            'Description',
            'Created At',
            'Updated At'
        ];
    }

    public function map($workCenter): array
    {
        return [
            $workCenter->id,
            $workCenter->name,
            $workCenter->code,
            $workCenter->working_hours,
            $workCenter->time_efficiency,
            $workCenter->cost_per_hour,
            $workCenter->capacity,
            $workCenter->oee_target,
            $workCenter->time_before_prod,
            $workCenter->time_after_prod,
            strip_tags($workCenter->description),
            $workCenter->created_at,
            $workCenter->updated_at,
        ];
    }
}
