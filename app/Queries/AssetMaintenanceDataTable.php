<?php

namespace App\Queries;

use App\Models\AssetMaintenance;
use Illuminate\Database\Eloquent\Builder;

class AssetMaintenanceDataTable
{
    public function get(): Builder
    {
        return AssetMaintenance::with(['asset', 'supplier'])
            ->select('asset_maintenances.*');
    }
}
