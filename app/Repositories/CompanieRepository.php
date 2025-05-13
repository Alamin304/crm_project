<?php

namespace App\Repositories;

use App\Models\Companie;
use Illuminate\Support\Facades\DB;

class CompanieRepository
{
    public function create(array $input)
    {
        return Companie::create($input);
    }

    public function update(array $input, $id)
    {
        $companie = Companie::findOrFail($id);
        $companie->update($input);
        return $companie;
    }
}
