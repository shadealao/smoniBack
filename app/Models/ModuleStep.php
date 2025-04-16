<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleStep extends Model
{
    protected $fillable = [
        'module_id', 'name', 'description', 'duration_minutes',
        'step_type', 'display_order', 'required_for_completion'
    ];

    protected $casts = [
        'required_for_completion' => 'boolean',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }
}
