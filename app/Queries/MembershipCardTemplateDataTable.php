<?php

namespace App\Queries;

use App\Models\MembershipCardTemplate;
use Illuminate\Database\Eloquent\Builder;

class MembershipCardTemplateQuery
{
    public function get(): Builder
    {
        return MembershipCardTemplate::query()
            // ->with('addedBy')
            ->select('membership_card_templates.*');
    }
}



