<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileItem extends Model
{
    use HasFactory;

    protected $fillable = ['file_section_id', 'parent_id', 'type', 'name', 'file_path'];

    public function section()
    {
        return $this->belongsTo(FileSection::class, 'file_section_id');
    }

    public function parent()
    {
        return $this->belongsTo(FileItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(FileItem::class, 'parent_id');
    }

    public function getBreadcrumbs()
{
    $breadcrumbs = collect();
    $current = $this->parent;

    while ($current) {
        $breadcrumbs->prepend($current);
        $current = $current->parent;
    }

    return $breadcrumbs;
}
}
