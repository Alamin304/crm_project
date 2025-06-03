<?php

namespace App\Repositories;

use App\Models\License;
use Illuminate\Support\Facades\DB;

class LicenseRepository
{
    public function create(array $input)
    {
        return License::create($input);
    }

    public function update(array $input, $id)
    {
        $license = License::findOrFail($id);
        $license->update($input);
        return $license;
    }
}