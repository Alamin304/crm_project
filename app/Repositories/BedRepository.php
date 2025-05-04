<?php

namespace App\Repositories;

use App\Models\Bed;
use Illuminate\Support\Facades\DB;

class BedRepository
{
    public function create(array $input)
    {
        return Bed::create($input);
    }

    public function update(array $input, $id)
    {
        $bed = Bed::findOrFail($id);
        $bed->update($input);
        return $bed;
    }
}
