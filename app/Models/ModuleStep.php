<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id', 'name', 'code', 'description', 'duration_minutes',
        'step_type', 'display_order', 'required_for_completion', 'validation_criteria', 'status','pdf'
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'display_order' => 'integer',
        'required_for_completion' => 'boolean',
        'validation_criteria' => 'array',
    ];

    public function module()
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }

    public function competences()
    {
        return $this->hasMany(StepModuleItem::class,'id');
    }
}
