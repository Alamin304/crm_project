<?php

namespace App\Queries;

use App\Models\Complementary;

class ComplementaryDataTable
{
    public function get()
    {
        return Complementary::select('id', 'room_type', 'complementary', 'rate', 'created_at');
    }
}
