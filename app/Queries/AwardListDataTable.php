<?php

namespace App\Queries;

use App\Models\AwardList;

class AwardListDataTable
{
    public function get()
    {
        return AwardList::select('id', 'award_name', 'award_description', 'gift_item', 'date', 'employee_name', 'award_by', 'created_at');
    }
}
