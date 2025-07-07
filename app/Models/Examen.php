<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = ['instructor_id', 'learner_id', 'date', 'datetime', 'status'];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function learner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'learner_id');
    }
    
}
