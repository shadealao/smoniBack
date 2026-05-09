<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizCategory extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'pass_score',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class, 'category_id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'category_id');
    }
}
