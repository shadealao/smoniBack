<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StepModuleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'step_id', 'order', 'is_critical','validation_criteria','description'
    ];

    public function currentStep()
    {
        return $this->belongsTo(ModuleStep::class, 'step_id');
    }
}
