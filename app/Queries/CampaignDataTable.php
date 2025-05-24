<?php

namespace App\Queries;

use App\Models\Campaign;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\EloquentDataTable;

class CampaignDataTable
{
    public function get(): Builder
    {
        return Campaign::query()->select('campaigns.*');
    }

    public function dataTable(Builder $query): DataTableAbstract
    {
        return (new EloquentDataTable($query))
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->rawColumns(['is_active']) // allow HTML rendering
            ->addIndexColumn();
    }
}
