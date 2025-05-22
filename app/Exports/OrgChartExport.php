<?php

namespace App\Exports;

use App\Models\OrgChart;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrgChartExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return OrgChart::all();
    }
}
