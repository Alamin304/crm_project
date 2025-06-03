<?php

namespace App\Queries;

use App\Models\SecondAsset;
use Illuminate\Database\Eloquent\Builder;

class SecondAssetDataTable
{
    public function get(): Builder
    {
        return SecondAsset::query()->select('second_assets.*');
    }
}
