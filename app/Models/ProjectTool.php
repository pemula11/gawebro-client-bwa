<?php

namespace App\Models;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectTool extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $fillable = ['project_id', 'tool_id'];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

}
