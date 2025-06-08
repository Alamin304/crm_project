<?php

namespace App\Repositories;

use App\Models\AssetMaintenance;

class AssetMaintenanceRepository
{
    public function create(array $input)
    {
        return AssetMaintenance::create($input);
    }

    public function update(array $input, $id)
    {
        $assetMaintenance = AssetMaintenance::findOrFail($id);
        $assetMaintenance->update($input);
        return $assetMaintenance;
    }
}
