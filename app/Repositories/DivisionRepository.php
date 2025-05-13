<?php

namespace App\Repositories;

use App\Models\Division;
use Illuminate\Support\Facades\DB;

class DivisionRepository
{
    public function create(array $input)
    {
        return Division::create($input);
    }

    public function update(array $input, $id)
    {
        $division = Division::findOrFail($id);
        $division->update($input);
        return $division;
    }
}
