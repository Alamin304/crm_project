<?php

namespace App\Exports;

use App\Models\JobPost;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class JobPostsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return JobPost::with('category')->orderBy('id')->get(); // include category relation
    }

    public function map($jobPost): array
    {
        $positionData = $jobPost->job_title . "\n" .
                        optional($jobPost->category)->name . "\n" .
                        $jobPost->no_of_vacancy . ' ' . __('messages.job_posts.vacancies');

        return [
            $positionData,
            $jobPost->company_name,
            optional($jobPost->created_at)->format('Y-m-d'),
            $jobPost->status ? __('messages.job_posts.active') : __('messages.job_posts.inactive'),
            optional($jobPost->date_of_closing)->format('Y-m-d'),
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.job_posts.position'),
            __('messages.job_posts.company'),
            __('messages.job_posts.posting_date'),
            __('messages.job_posts.status'),
            __('messages.job_posts.date_of_closing'),
        ];
    }
}
