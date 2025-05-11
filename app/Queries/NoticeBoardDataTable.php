<?php

namespace App\Queries;

use App\Models\NoticeBoard;
use Illuminate\Database\Eloquent\Builder;

class NoticeBoardDataTable
{
    public function get(): Builder
    {
        return NoticeBoard::query()->select('notice_boards.*');
    }
}
