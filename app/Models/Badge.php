<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Badge extends Model
{
    protected $fillable = [
        'learner_id', 'module_id', 'awarded_at',
        'validation_instructor_id', 'certification_url'
    ];

    protected $casts = [
        'awarded_at' => 'datetime',
    ];

    public function learner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validation_instructor_id');
    }
}
