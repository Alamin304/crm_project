<?php

namespace App\Queries;

use App\Models\LoyaltyUser;
use Illuminate\Database\Eloquent\Builder;

class loyaltyUserDataTable
{
    public function get(): Builder
    {
        return LoyaltyUser::query()->select('loyalty_users.*');
    }
}
