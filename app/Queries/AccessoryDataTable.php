<?php

namespace App\Queries;

use App\Models\Accessory;
use Illuminate\Database\Eloquent\Builder;

class AccessoryDataTable
{
    public function get(): Builder
    {
        return Accessory::query()->select('accessories.*');
    }
}
