<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeBoard extends Model
{
    use HasFactory;
    protected $fillable = [
        'notice_type',
        'description',
        'notice_date',
        'notice_by',
        'notice_attachment'
    ];
}
