<?php

namespace App\Queries;

use App\Models\Request;
use Illuminate\Database\Eloquent\Builder;

class RequestDataTable
{
    public function get(): Builder
    {
        return Request::query()->select('requests.*');
    }
}
