<?php

namespace App\Queries;

use App\Models\MaterialCode;
use Illuminate\Database\Eloquent\Builder;

class MaterialCodeDataTable
{
    public function get(): Builder
    {
        return MaterialCode::query()->select('material_codes.*');
    }
}
