<?php

namespace App\Repositories;

use App\Models\MembershipRule;
use Illuminate\Support\Facades\Auth;

/**
 * Class MembershipRuleRepository
 */
class MembershipRuleRepository
{
     public function create(array $input)
    {
        return MembershipRule::create($input);
    }

    public function update(array $input, $id)
    {
        $membershipRule = MembershipRule::findOrFail($id);
        $membershipRule->update($input);
        return $membershipRule;
    }
}
