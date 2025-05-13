<?php

namespace App\Queries;

use App\Models\Group;
use Illuminate\Database\Eloquent\Builder;

class GroupDataTable
{
    public function get(): Builder
    {
        return Group::query()->select('groups.*');
    }
}
