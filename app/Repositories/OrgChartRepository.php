<?php

namespace App\Repositories;

use App\Models\OrgChart;
use App\Repositories\BaseRepository;

class OrgChartRepository
{

    public function create($input)
    {
        return OrgChart::create($input);
    }

    public function update($input, $id)
    {
        $orgChart = OrgChart::findOrFail($id);
        $orgChart->update($input);
        return $orgChart;
    }
}

