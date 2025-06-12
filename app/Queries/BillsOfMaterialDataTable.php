<?php

namespace App\Queries;

use App\Models\BillsOfMaterial;
use Illuminate\Database\Eloquent\Builder;

class BillsOfMaterialDataTable
{
    public function get(): Builder
    {
        return BillsOfMaterial::query()->select('bills_of_materials.*');
    }
}
