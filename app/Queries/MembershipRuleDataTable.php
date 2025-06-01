<?php

namespace App\Queries;

use App\Models\MembershipRule;
use Illuminate\Database\Eloquent\Builder;

class MembershipRuleDataTable
{
    public function get(): Builder
    {
        return MembershipRule::query()->select('membership_rules.*');
    }
}
