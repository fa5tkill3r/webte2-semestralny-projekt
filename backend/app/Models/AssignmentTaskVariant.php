<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentTaskVariant extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'assignment_id',
        'task_variant_id',
        'set_task_id',
        'solution',
        'correct',
    ];


    /**
     * @return BelongsTo
     */
    public function setTask(): BelongsTo
    {
        return $this->belongsTo(SetTask::class);
    }

    public function taskVariant(): BelongsTo
    {
        return $this->belongsTo(TaskVariant::class);
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }
}
