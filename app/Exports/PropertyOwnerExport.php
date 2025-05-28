<?php

namespace App\Exports;

use App\Models\PropertyOwner;
use Maatwebsite\Excel\Concerns\FromCollection;

class PropertyOwnerExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PropertyOwner::all();
    }
}
