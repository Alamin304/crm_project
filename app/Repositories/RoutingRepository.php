<?php

namespace App\Repositories;

use App\Models\Routing;
use Illuminate\Support\Facades\DB;

class RoutingRepository
{
    public function create(array $input)
    {
        return Routing::create($input);
    }

    public function update(array $input, $id)
    {
        $routing = Routing::findOrFail($id);
        $routing->update($input);
        return $routing;
    }
}
