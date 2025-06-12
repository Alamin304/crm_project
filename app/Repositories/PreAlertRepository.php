<?php

namespace App\Repositories;

use App\Models\PreAlert;

class PreAlertRepository
{
    public function create(array $input)
    {
        return PreAlert::create($input);
    }

    public function update(array $input, $id)
    {
        $preAlert = PreAlert::findOrFail($id);
        $preAlert->update($input);
        return $preAlert;
    }
}
