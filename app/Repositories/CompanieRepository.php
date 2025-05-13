<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanieRepository
{
    public function create(array $input)
    {
        return Company::create($input);
    }

    public function update(array $input, $id)
    {
        $Company = Company::findOrFail($id);
        $Company->update($input);
        return $Company;
    }
}
