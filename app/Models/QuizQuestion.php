<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestion extends Model
{
    protected $fillable = [
        'category_id',
        'question_number',
        'question_text',
        'correct_answer',
        'correct_option_index',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(QuizCategory::class, 'category_id');
    }
}
