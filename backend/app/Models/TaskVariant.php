<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskVariant extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'task',
        'solution',
        'task_id',
    ];

    protected $hidden = [
        'solution',
        'pivot'
    ];



    public function task()
    {
        return $this->belongsTo(Task::class);
    }



    public function setTasks()
    {
        return $this->belongsToMany(SetTask::class, 'set_tasks', 'task_variant_id', 'task_id');
    }


}
