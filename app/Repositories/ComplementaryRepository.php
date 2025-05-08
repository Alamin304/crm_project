<?php

namespace App\Repositories;

use App\Models\Complementary;

class ComplementaryRepository
{
    public function create(array $input)
    {
        return Complementary::create($input);
    }

    public function update(array $input, $id)
    {
        $complementary = Complementary::findOrFail($id);
        $complementary->update($input);
        return $complementary;
    }
}
