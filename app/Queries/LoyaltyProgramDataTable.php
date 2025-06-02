<?php

namespace App\Queries;

use App\Models\LoyaltyProgram;
use Illuminate\Database\Eloquent\Builder;

class LoyaltyProgramDataTable
{
    public function get(): Builder
    {
        return LoyaltyProgram::query()->select('loyalty_programs.*');
    }
}

