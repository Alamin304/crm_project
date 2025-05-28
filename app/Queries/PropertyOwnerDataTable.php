<?php

namespace App\Queries;

use App\Models\PropertyOwner;
use App\Models\Warranty;
use Illuminate\Database\Eloquent\Builder;

class PropertyOwnerDataTable
{
    public function get(): Builder
    {
        return PropertyOwner::query()->select('property_owners.*');
    }
}
