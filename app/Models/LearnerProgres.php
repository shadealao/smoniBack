<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearnerProgres extends Model
{
    protected $fillable = [
        'learner_id', 'module_id', 'current_step_id',
        'status', 'started_at', 'completed_at', 'instructor_notes'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function learner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(ModuleStep::class, 'current_step_id');
    }
}
