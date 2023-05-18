<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetTask extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['set_id', 'task_id', 'max_points'];

    public function set()
    {
        return $this->belongsTo(Set::class, 'set_id');
    }
    
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
