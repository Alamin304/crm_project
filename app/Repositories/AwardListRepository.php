<?php

namespace App\Repositories;

use App\Models\AwardList;

class AwardListRepository
{
    public function create(array $input)
    {
        return AwardList::create($input);
    }

    public function update(array $input, $id)
    {
        $awardList = AwardList::findOrFail($id);
        $awardList->update($input);
        return $awardList;
    }
}
