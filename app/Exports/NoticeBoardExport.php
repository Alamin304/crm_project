<?php

namespace App\Exports;

use App\Models\NoticeBoard;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NoticeBoardExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return NoticeBoard::query()
            ->orderBy('id')
            ->get();
    }

    public function map($notice): array
    {
        return [
            $notice->id,
            $notice->notice_type,
            strip_tags($notice->description), // Remove HTML tags
            $notice->notice_date,
            $notice->notice_by,
            // $notice->notice_attachment ? basename($notice->notice_attachment) : 'N/A',
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.notice_boards.id'),
            __('messages.notice_boards.notice_type'),
            __('messages.notice_boards.description'),
            __('messages.notice_boards.notice_date'),
            __('messages.notice_boards.notice_by'),
            // __('messages.notice_boards.notice_attachment'),
        ];
    }
}
