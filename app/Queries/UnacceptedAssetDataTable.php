<?php

namespace App\Queries;

use App\Models\UnacceptedAsset;
use Illuminate\Database\Eloquent\Builder;

class UnacceptedAssetDataTable
{
    public function get(): Builder
    {
        return UnacceptedAsset::query()->select('unaccepted_assets.*');
    }
}
