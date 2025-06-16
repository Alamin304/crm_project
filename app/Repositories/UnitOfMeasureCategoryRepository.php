<?php
namespace App\Repositories;

use App\Models\UnitOfMeasureCategory;

class UnitOfMeasureCategoryRepository
{
    public function create(array $input)
    {
        return UnitOfMeasureCategory::create($input);
    }

    public function update(array $input, $id)
    {
        $category = UnitOfMeasureCategory::findOrFail($id);
        $category->update($input);
        return $category;
    }
}
