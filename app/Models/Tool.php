<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tool extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'slug', 'icon'];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_tools', 'tool_id', 'project_id')
            ->wherePivotNull('deleted_at')
            ->withPivot('id');
    }
}
