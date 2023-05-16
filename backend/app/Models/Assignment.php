<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'finished_at',
        'based_on_set_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignmentTaskVariants(): HasMany
    {
        return $this->hasMany(AssignmentTaskVariant::class);
    }

    public function set(): BelongsTo
    {
        return $this->belongsTo(Set::class, 'based_on_set_id');
    }


}
