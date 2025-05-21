<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TrainingProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_name',
        'training_type',
        'program_items',
        'point',
        'departments',
        'apply_position',
        'description',
        'staff_name',
        'start_date',
        'finish_date',
        'attachment',
        'is_active',
        'training_mode',
        'max_participants'
    ];

    protected $casts = [
        'program_items' => 'array',
        'departments' => 'array',
        'start_date' => 'date',
        'finish_date' => 'date',
    ];

    public function getAttachmentUrlAttribute()
    {
        if ($this->attachment) {
            return Storage::url($this->attachment);
        }
        return null;
    }
}
