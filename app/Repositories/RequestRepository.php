<?php

namespace App\Repositories;

use App\Models\Request;

class RequestRepository
{
    public function create(array $input)
    {
        return Request::create($input);
    }

    public function update(array $input, $id)
    {
        $request = Request::findOrFail($id);
        $request->update($input);
        return $request;
    }
}
