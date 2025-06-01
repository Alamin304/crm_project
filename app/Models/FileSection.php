<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileSection extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // public function items()
    // {
    //     return $this->hasMany(FileItem::class);
    // }
    public function items()
{
    return $this->hasMany(FileItem::class, 'file_section_id')
                ->whereNull('parent_id')
                ->where('type', 'folder');
}

}
