<?php

namespace App\Repositories;

use App\Models\Group;

class GroupRepository
{
    public function create(array $input)
    {
        return Group::create($input);
    }

    public function update(array $input, $id)
    {
        $group = Group::findOrFail($id);
        $group->update($input);
        return $group;
    }

    public function delete($id)
    {
        $group = Group::findOrFail($id);
        return $group->delete();
    }
}
