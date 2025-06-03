<?php

namespace App\Repositories;

use App\Models\SecondAsset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SecondAssetRepository extends BaseRepository
{
    public $fieldSearchable = [
        'serial_number',
        'asset_name',
        'model',
        'status',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return SecondAsset::class;
    }

    public function store($input)
    {
        try {
            DB::beginTransaction();

            $asset = SecondAsset::create($input);

            DB::commit();

            return $asset;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($input, $id)
    {
        try {
            DB::beginTransaction();

            $asset = SecondAsset::findOrFail($id);
            $asset->update($input);

            DB::commit();

            return $asset;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
