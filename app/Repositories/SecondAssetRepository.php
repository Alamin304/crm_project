<?php

namespace App\Repositories;

use App\Models\SecondAsset;

class SecondAssetRepository
{
    public function create(array $input)
    {
        return SecondAsset::create($input);
    }

    public function update(array $input, $id)
    {
        $asset = SecondAsset::findOrFail($id);
        $asset->update($input);
        return $asset;
    }
}
