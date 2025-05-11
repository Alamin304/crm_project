<?php

namespace App\Repositories;

use App\Models\NoticeBoard;
use Illuminate\Support\Facades\Auth;

class NoticeBoardRepository
{
    public function create($input)
    {
        // $input['notice_by'] = Auth::user()->name;
        return NoticeBoard::create($input);
    }

    public function update($input, $id)
    {
        $noticeBoard = NoticeBoard::findOrFail($id);
        $noticeBoard->update($input);
        return $noticeBoard;
    }
}
