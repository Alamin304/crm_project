<?php

namespace App\Repositories;

use App\Models\Warranty;
use Illuminate\Support\Facades\Auth;

class WarrantyRepository
{
    public function create($input)
    {
        // $input['created_by'] = Auth::id();
        return Warranty::create($input);
    }

    public function update($input, $id)
    {
        $warranty = Warranty::findOrFail($id);
        $warranty->update($input);
        return $warranty;
    }
}
