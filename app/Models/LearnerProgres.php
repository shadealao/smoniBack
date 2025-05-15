<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearnerProgres extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitor_id', 'learner_id', 'module_id', 'current_step_id', 'status',
        'started_at', 'completed_at', 'instructor_notes', 'is_completed'
    ];

    protected $casts = [
        'status' => 'string',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function monitor()
    {
        return $this->belongsTo(User::class, 'monitor_id');
    }

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function module()
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }

    public function currentStep()
    {
        return $this->belongsTo(ModuleStep::class, 'current_step_id');
    }
}
