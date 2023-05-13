<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assignment extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'finished_at',
        'based_on_set_id',
    ];

    protected $with = [
        'set',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignmentTaskVariants(): BelongsToMany
    {
        return $this->belongsToMany(AssignmentTaskVariant::class, 'assignment_task_variants', 'assignment_id', 'task_variant_id');
    }

    public function taskVariants(): BelongsToMany
    {
        return $this->belongsToMany(TaskVariant::class, 'assignment_task_variants', 'assignment_id', 'task_variant_id');
    }

    public function setTask($setTaskNumber): SetTask
    {
        return $this->set()->first()->setTasks()->where('task_number', $setTaskNumber)->first();
    }

    public function set(): BelongsTo
    {
        return $this->belongsTo(Set::class, 'based_on_set_id');
    }


}
