<?php

// app/Exports/UnacceptedAssetsExport.php

namespace App\Exports;

use App\Models\UnacceptedAsset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UnacceptedAssetsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return UnacceptedAsset::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Asset',
            'Image',
            'Serial Number',
            'Checkout For',
            'Notes',
            'Created At',
            'Updated At'
        ];
    }
}
