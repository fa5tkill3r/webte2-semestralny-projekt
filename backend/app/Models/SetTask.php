<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetTask extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'set_id',
        'task_id',
    ];

    public function set()
    {
        return $this->belongsTo(Set::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
