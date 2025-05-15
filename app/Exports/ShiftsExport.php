<?php

namespace App\Exports;

use App\Models\Shift;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ShiftsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Shift::orderBy('id')->get();
    }

    public function map($shift): array
    {
        static $index = 0;
        $index++;
        return [
            $index,
            $shift->name,
            $this->formatTime($shift->shift_start_time),
            $this->formatTime($shift->shift_end_time),
            $this->calculateDuration($shift->shift_start_time, $shift->shift_end_time),
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.shifts.id'),
            __('messages.shifts.name'),
            __('messages.shifts.shift_start_time'),
            __('messages.shifts.shift_end_time'),
            __('messages.shifts.duration'),
        ];
    }

    protected function formatTime($time)
    {
        return $time ? $time->format('h:i A') : '';
    }

    protected function calculateDuration($startTime, $endTime)
    {
        if (!$startTime || !$endTime) return '';

        // Clone to avoid modifying the original objects
        $start = clone $startTime;
        $end = clone $endTime;

        // Handle overnight shifts
        if ($end < $start) {
            $end->addDay();
        }

        $diff = $end->diff($start);
        $hours = $diff->h + ($diff->i / 60);

        return round($hours, 1) . ' hours';
    }
}
