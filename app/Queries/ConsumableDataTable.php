<?php

namespace App\Queries;

use App\Models\Consumable;
use Illuminate\Database\Eloquent\Builder;

class ConsumableDataTable
{
    public function get(): Builder
    {
        return Consumable::query()->select('consumables.*');
    }
}
