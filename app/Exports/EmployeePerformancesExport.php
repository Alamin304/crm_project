<?php

namespace App\Exports;

use App\Models\EmployeePerformance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmployeePerformancesExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return EmployeePerformance::with('employee')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function map($performance): array
    {
        static $index = 0;
        $index++;
        return [
            $index,
            $performance->employee->name ?? 'N/A',
            $performance->total_score,
            $performance->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.employee_performances.id'),
            __('messages.employee_performances.name'),
            __('messages.employee_performances.total_score'),
            __('messages.employee_performances.created_at'),
        ];
    }
}
