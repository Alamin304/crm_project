<?php

namespace App\Exports;

use App\Models\TrainingProgram;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TrainingProgramExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return TrainingProgram::orderBy('id')->get([
            'program_name',
            'training_type',
            'description',
            'point',
            'created_at'
        ]);
    }

    public function map($program): array
    {
        return [
            $program->program_name,
            $program->training_type,
            strip_tags($program->description),
            $program->point,
            $program->created_at->format('Y-m-d'),
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.training_programs.program_name'),
            __('messages.training_programs.training_type'),
            __('messages.training_programs.description'),
            __('messages.training_programs.point'),
            __('messages.training_programs.created_at'),
        ];
    }
}
