<?php
namespace App\Queries;

use App\Models\Recipient;
use Illuminate\Database\Eloquent\Builder;

class RecipientDataTable
{
    public function get(): Builder
    {
        return Recipient::query()->select('recipients.*');
    }
}
