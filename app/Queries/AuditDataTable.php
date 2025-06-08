<?php
namespace App\Queries;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Builder;

class AuditDataTable
{
    public function get(): Builder
    {
        return Audit::query()->select('audits.*');
    }
}
