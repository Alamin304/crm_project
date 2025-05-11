<?php

namespace App\Exports;

use App\Models\JobCategory;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class JobCategoriesExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return JobCategory::query()
            ->orderBy('id')
            ->get();
    }

    public function map($jobCategory): array
    {
        return [
            $jobCategory->id,
            $jobCategory->name,
            strip_tags($jobCategory->description), // Remove HTML tags
            Carbon::parse($jobCategory->start_date)->format('Y-m-d'), 
            Carbon::parse($jobCategory->end_date)->format('Y-m-d'),
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.job_categories.id'),
            __('messages.job_categories.name'),
            __('messages.job_categories.description'),
            __('messages.job_categories.start_date'),
            __('messages.job_categories.end_date'),
        ];
    }
}
