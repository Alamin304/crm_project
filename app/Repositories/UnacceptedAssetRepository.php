<?php

namespace App\Repositories;

use App\Models\UnacceptedAsset;
use Illuminate\Support\Facades\DB;

class UnacceptedAssetRepository
{
    public function create(array $input)
    {
        return UnacceptedAsset::create($input);
    }

    public function update(array $input, $id)
    {
        $asset = UnacceptedAsset::findOrFail($id);
        $asset->update($input);
        return $asset;
    }

    public function find($id)
    {
        return UnacceptedAsset::findOrFail($id);
    }
}
