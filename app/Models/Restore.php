<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restore extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'restores';

    // The attributes that are mass assignable
    protected $fillable = [
        'file_name',
        'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
