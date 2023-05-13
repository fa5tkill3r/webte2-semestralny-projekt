<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'created_at',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(TaskVariant::class);
    }

    public function randomVariant(): TaskVariant
    {
        return $this->variants()->inRandomOrder()->get()->first();
    }
}
