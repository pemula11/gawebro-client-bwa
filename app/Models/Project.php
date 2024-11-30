<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'category_id',
        'name',
        'slug',
        'thumbnail',
        'about',
        'budget',
        'start_date',
        'end_date',
        'status',
    ];

    public function applicants(){
        return $this->hasMany(ProjectApplicant::class);
    }

    public function tools(){
        return $this->belongsToMany(Tool::class, 'project_tools', 'project_id', 'tool_id')
            ->wherePivotNull('deleted_at')
            ->withPivot('id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }
}
