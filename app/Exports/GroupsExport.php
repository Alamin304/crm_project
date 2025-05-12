<?php

namespace App\Exports;

use App\Models\Group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GroupsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Group::orderBy('group_name', 'asc')->get();
    }

    public function map($group): array
    {
        return [
            $group->id,
            $group->group_name,
            $this->cleanDescription($group->description),
        ];
    }

    public function headings(): array
    {
        return [
            __('messages.groups.id'),
            __('messages.groups.group_name'),
            __('messages.groups.description'),
        ];
    }

    protected function cleanDescription($description): string
    {
        $cleaned = strip_tags($description);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        return trim($cleaned);
    }
}
